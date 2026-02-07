<?php

namespace Customize\Controller\Admin\Invoice;

use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\BusinessStaff;
use Customize\Entity\InvoiceIssued;
use Customize\Entity\InvoiceIssueAddItem;
use Customize\Entity\Report;
use Customize\Form\Type\Admin\InvoiceIssueType;
use Customize\Form\Type\Admin\InvoiceIssueAddItemType;
use Customize\Repository\Master\InvoiceAddItemsRepository;
use Customize\Repository\BusinessStaffRepository;
use Customize\Repository\InvoiceIssuedRepository;
use Customize\Repository\InvoiceIssueAddItemRepository;
use Customize\Repository\ReportRepository;
use Customize\Service\FileUploader;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Customer;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\MailTemplateRepository;
use Eccube\Service\MailService;
use Eccube\Util\StringUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InvoiceIssueEditController extends AbstractController
{
    /**
     * @var InvoiceIssuedRepository
     */
    protected $invoiceIssuedRepository;

    /**
     * @var InvoiceIssueAddItemRepository
     */
    protected $invoiceIssueAddItemRepository;

    /**
     * @var InvoiceAddItemsRepository
     */
    protected $invoiceAddItemsRepository;

    /**
     * @var BusinessStaffRepository
     */
    protected $businessStaffRepository;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @var MailTemplateRepository
     */
    private $mailTemplateRepository;

    /**
     * @var \Twig_Environment
     */
    private $twig;


    public function __construct(
        BusinessStaffRepository $businessStaffRepository,
        InvoiceIssuedRepository $invoiceIssuedRepository,
        InvoiceAddItemsRepository $invoiceAddItemsRepository,
        InvoiceIssueAddItemRepository $invoiceIssueAddItemRepository,
        ReportRepository $reportRepository,
        CustomerRepository $customerRepository,
        BaseInfoRepository $baseInfoRepository,
        MailService $mailService,
        FileUploader $fileUploader,
        MailTemplateRepository $mailTemplateRepository,
        ValidatorInterface $validator
    ) {
        $this->businessStaffRepository = $businessStaffRepository;
        $this->invoiceIssuedRepository = $invoiceIssuedRepository;
        $this->invoiceAddItemsRepository = $invoiceAddItemsRepository;
        $this->invoiceIssueAddItemRepository = $invoiceIssueAddItemRepository;
        $this->reportRepository = $reportRepository;
        $this->customerRepository = $customerRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->mailService = $mailService;
        $this->fileUploader = $fileUploader;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/invoice/{id}/issue/edit/{issue_ym}", requirements={"id" = "\d+"}, name="admin_invoice_issue_edit", methods={"GET", "POST"})
     * @Template("@admin/Invoice/issue_edit.twig")
     */
     public function index(Request $request, $id = null, $issue_ym = null)
     {
         if( $issue_ym == null ) $issue_ym = date('Y-m', strtotime('-1 month'));

         $config = $this->eccubeConfig;
         $Invoice = new InvoiceIssueAddItem();
         if ($id) {
             $Customer = $this->customerRepository->find($id);
             if (is_null($Customer)) {
                 throw new NotFoundHttpException();
             }
             $BusinessStaff = $this->businessStaffRepository->getBusinessStaffByThisMonth([
                     'Customer' => $Customer,
                     'working_ym' => $issue_ym,
             ]);
             $Report = $this->reportRepository->getMonthlyReportByCustomer([
                     'Customer' => $Customer,
                     'working_ym' => $issue_ym,
             ]);

         }else{
             $BusinessStaff = new BusinessStaff();
             $Report = new Report();
         }
         // 給与明細登録フォーム
         $builder = $this->formFactory
             ->createBuilder(InvoiceIssueType::class, $Invoice);
         $form = $builder->getForm();
         $form->handleRequest($request);
         $InvoiceAddItems = $this->invoiceAddItemsRepository->findAll();

         return [
             'form' => $form->createView(),
             'BusinessStaff' => $BusinessStaff,
             'Customer' => $Customer,
             'Invoice' => $Invoice,
             'Report' => $Report,
             'InvoiceAddItems' => $InvoiceAddItems,
             'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
             'AdminReportFormToken' => $config['admin_report_form_token'],
//             'AdminReportFormToken' => 'ADMIN_REPORT_FORM_TOKEN',
         ];
     }

     /**
      * @Route("/%eccube_admin_route%/invoice/{id}/issue/issued/{issue_ym}", requirements={"id" = "\d+"}, name="admin_invoice_issue_issued", methods={"GET", "POST"})
      * @Template("@admin/Invoice/issue_edit.twig")
      */
      public function issued(Request $request, $id = null, $issue_ym = null)
      {
          if( $issue_ym == null ) $issue_ym = date('Y-m', strtotime('-1 month'));

          if ($id) {
              $Customer = $this->customerRepository->find($id);
              // 請求書発行テーブルにデータセット
              $InvoiceIssued = new InvoiceIssued();
              $InvoiceIssued->setCustomer( $Customer );
              $InvoiceIssued->setIssueYm( new \DateTime( $issue_ym. '-10' ) );
              $InvoiceIssued->setKindId(2);
              $InvoiceIssued->setPaymentType(2);
/*
              $InvoiceIssued->setAmountTotal();
              $InvoiceIssued->setAmountTax();
              $InvoiceIssued->setAmountBasic();
              $InvoiceIssued->setAmountOvertime();
              $InvoiceIssued->setAmountFare();
              $InvoiceIssued->setAmountMessage();
              $InvoiceIssued->setAmountNote();
*/
              $InvoiceIssued->setStatus(1);
              $InvoiceIssued->setRevision(1);
              $this->entityManager->persist($InvoiceIssued);
              $this->entityManager->flush();

              // 該当の業務報告書に発行済みステータスをセット
              $Report = $this->reportRepository->setIssueMonthlyReportByCustomer([
                      'Customer' => $Customer,
                      'working_ym' => $issue_ym,
              ]);
          }
          return $this->redirectToRoute('admin_invoice_issue');

      }

      /**
       * @Route("/%eccube_admin_route%/invoice/{id}/issue/view/{issue_ym}", requirements={"id" = "\d+"}, name="admin_invoice_issue_view", methods={"GET", "POST"})
       * @Template("@admin/Invoice/issue_view.twig")
       */
       public function view(Request $request, $id = null, $issue_ym = null)
       {
           if( $issue_ym == null ) $issue_ym = date('Y-m', strtotime('-1 month'));
           $issue_ymd = $issue_ym. '-04';

           $config = $this->eccubeConfig;
           $Invoice = new InvoiceIssueAddItem();
           if ($id) {
               $Customer = $this->customerRepository->find($id);
               if (is_null($Customer)) {
                   throw new NotFoundHttpException();
               }
               $BusinessStaff = $this->businessStaffRepository->getBusinessStaffByThisMonth([
                       'Customer' => $Customer,
                       'working_ym' => $issue_ym,
               ]);
               $Report = $this->reportRepository->getMonthlyReportByCustomer([
                       'Customer' => $Customer,
                       'working_ym' => $issue_ym,
               ]);

           }else{
               $BusinessStaff = new BusinessStaff();
               $Report = new Report();
           }
           //
           $builder = $this->formFactory
               ->createBuilder(InvoiceIssueType::class, $Invoice);
           $form = $builder->getForm();
           $form->handleRequest($request);
           $InvoiceAddItems = $this->invoiceAddItemsRepository->findAll();

           return [
               'form' => $form->createView(),
               'BusinessStaff' => $BusinessStaff,
               'Customer' => $Customer,
               'Invoice' => $Invoice,
               'Report' => $Report,
               'issue_ymd' => $issue_ymd,
               'InvoiceAddItems' => $InvoiceAddItems,
               'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
               'AdminReportFormToken' => $config['admin_report_form_token'],
  //             'AdminReportFormToken' => 'ADMIN_REPORT_FORM_TOKEN',
           ];
       }


 }
