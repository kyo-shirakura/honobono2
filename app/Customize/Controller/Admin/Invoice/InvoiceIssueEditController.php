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
use Customize\Repository\BusinessPlanRepository;
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
     * @var BusinessPlanRepository
     */
    protected $businessPlanRepository;

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
        BusinessPlanRepository $businessPlanRepository,
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
        $this->businessPlanRepository = $businessPlanRepository;
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

/*
  // 編集
  if ($id) {
      $InvoiceIssued = $this->invoiceIssuedRepository
          ->find($id);

      if (is_null($Invoice)) {
          throw new NotFoundHttpException();
      }
  // 新規登録
  } else {
      $Invoice = new Invoice();
      $Invoice->setRevision(0);
      if( isset( $_GET['cid'] ) && $_GET['cid'] != '' ){
          $Invoice->setCustomer( $this->customerRepository->find( $_GET['cid'] ) );
      }
  }
*/

         if (is_null($id)) {
           throw new NotFoundHttpException();
         }

         $config = $this->eccubeConfig;
         $Invoice = new InvoiceIssueAddItem();

         $InvoiceIssud = $this->invoiceIssuedRepository->find($id);

         $Customer = $this->customerRepository->find($InvoiceIssud->getCustomer());
         if (is_null($Customer)) {
             throw new NotFoundHttpException();
         }

         if( $issue_ym == null ){
           // 基本料金を取得
           $BusinessPlan = $this->businessPlanRepository->getActivePlan([
               'contractor_id' => $InvoiceIssud->getBusiness()->getContractorId()   // 契約対象(法人/個人)
               ,'kind_id' => $InvoiceIssud->getBusiness()->getKindId() // 契約種類(定期/単発)
           ]);

           $working_ym = date('Y-m', strtotime('-1 month'));
           $issue_ym = date('Y-m', strtotime('+1 month'));
           $issue_ymd = $issue_ym. '-'. $BusinessPlan->getTransferDate();
         }

         $BusinessStaff = $this->businessStaffRepository->getBusinessStaffByThisMonth([
                 'Customer' => $Customer,
                 'working_ym' => $issue_ym,
         ]);
         $Report = $this->reportRepository->getMonthlyReportByCustomer([
                 'Customer' => $Customer,
                 'working_ym' => $working_ym,
         ]);
  //echo '<br>working_ym is '. $working_ym;
  //echo '<br>ReportList is '. count($Report);
         //
         $builder = $this->formFactory
             ->createBuilder(InvoiceIssueType::class, $Invoice);
         $form = $builder->getForm();
         $form->handleRequest($request);
         $InvoiceAddItems = $this->invoiceAddItemsRepository->findAll();

         return [
             'form' => $form->createView(),
             'BusinessPlan' => $BusinessPlan,
             'BusinessStaff' => $BusinessStaff,
             'Customer' => $Customer,
             'Invoice' => $Invoice,
             'Report' => $Report,
             'issue_ymd' => $issue_ymd,
             'InvoiceAddItems' => $InvoiceAddItems,
             'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
  ///               'AdminReportFormToken' => $config['admin_report_form_token'],
  //             'AdminReportFormToken' => 'ADMIN_REPORT_FORM_TOKEN',
         ];

/*
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
*/
     }

     /**
      * @Route("/%eccube_admin_route%/invoice/{id}/issue/issued", requirements={"id" = "\d+"}, name="admin_invoice_issue_complete", methods={"GET", "POST"})
      * @Template("@admin/Invoice/issue_complete.twig")
      */
      public function issue_complete(Request $request, $id = null, $issue_ym = null)
      {
          // 新規登録
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

//          return $this->redirectToRoute('admin_invoice_issue');


/*
        $InvoiceIssued = new InvoiceIssued();
        $InvoiceIssued->setRevision(0);
        if( isset( $_GET['cid'] ) && $_GET['cid'] != '' ){
            $InvoiceIssued->setCustomer( $this->customerRepository->find( $_GET['cid'] ) );
        }

        // 請求書登録フォーム
        $builder = $this->formFactory
            ->createBuilder(InvoiceType::class, $Invoice);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Invoice' => $Invoice,
            ],
            $request
        );

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            log_info('請求書登録開始', [$Invoice->getId()]);

           $Customer = $this->customerRepository->find( $Invoice->getCustomer()->getId() );
           $Invoice->setIssueYm( $Invoice->getIssueYm()->modify('+1 day') );
           $issue_ym = $Invoice->getIssueYm()->format("Ym");

           // ファイルアップロード
           $file = $form["file"]->getData();
           $filename = null;
           if($file) {
               $filename = $this->fileUploader->invoiceUpload( $file, $issue_ym, $Customer->getId() );
           }
            if( $filename !== null ){
                $Invoice->setPdfFile( $filename );
            }

            $this->entityManager->persist($Invoice);
            $this->entityManager->flush();

            // メール送信
            if( $Invoice->getSendmail() ){
                $this->mailService->sendInvoiceMail( $Invoice, $Customer );
            }

            log_info('請求書登録完了', [$Invoice->getId()]);

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_invoice_edit', [
                'id' => $Invoice->getId(),
            ]);
        }

          return [
              'form' => $form->createView(),
              'InvoiceIssued' => $InvoiceIssued,
          ];
*/

          echo 'issue_complete';
      }

     /**
      * @Route("/%eccube_admin_route%/invoice/issued/{id}/issued/{issue_ym}", requirements={"id" = "\d+"}, name="admin_invoice_issue_issued", methods={"GET", "POST"})
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
       * @Route("/%eccube_admin_route%/invoice/issued/{id}/view/{issue_ym}", requirements={"id" = "\d+"}, name="admin_invoice_issue_view", methods={"GET", "POST"})
       * @Template("@admin/Invoice/issue_view.twig")
       */
       public function view(Request $request, $id = null, $issue_ym = null)
       {
           if (is_null($id)) {
             throw new NotFoundHttpException();
           }

           $config = $this->eccubeConfig;
           $Invoice = new InvoiceIssueAddItem();

           $InvoiceIssud = $this->invoiceIssuedRepository->find($id);

           $Customer = $this->customerRepository->find($InvoiceIssud->getCustomer());
           if (is_null($Customer)) {
               throw new NotFoundHttpException();
           }

           if( $issue_ym == null ){
             // 基本料金を取得
             $BusinessPlan = $this->businessPlanRepository->getActivePlan([
                 'contractor_id' => $InvoiceIssud->getBusiness()->getContractorId()   // 契約対象(法人/個人)
                 ,'kind_id' => $InvoiceIssud->getBusiness()->getKindId() // 契約種類(定期/単発)
             ]);

             $working_ym = date('Y-m', strtotime('-1 month'));
             $issue_ym = date('Y-m', strtotime('+1 month'));
             $issue_ymd = $issue_ym. '-'. $BusinessPlan->getTransferDate();
           }

           $BusinessStaff = $this->businessStaffRepository->getBusinessStaffByThisMonth([
                   'Customer' => $Customer,
                   'working_ym' => $issue_ym,
           ]);
           $Report = $this->reportRepository->getMonthlyReportByCustomer([
                   'Customer' => $Customer,
                   'working_ym' => $working_ym,
           ]);
 //echo '<br>working_ym is '. $working_ym;
 //echo '<br>ReportList is '. count($Report);
           //
           $builder = $this->formFactory
               ->createBuilder(InvoiceIssueType::class, $Invoice);
           $form = $builder->getForm();
           $form->handleRequest($request);
           $InvoiceAddItems = $this->invoiceAddItemsRepository->findAll();

           return [
               'form' => $form->createView(),
               'BusinessPlan' => $BusinessPlan,
               'BusinessStaff' => $BusinessStaff,
               'Customer' => $Customer,
               'Invoice' => $Invoice,
               'Report' => $Report,
               'issue_ymd' => $issue_ymd,
               'InvoiceAddItems' => $InvoiceAddItems,
               'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
///               'AdminReportFormToken' => $config['admin_report_form_token'],
  //             'AdminReportFormToken' => 'ADMIN_REPORT_FORM_TOKEN',
           ];
       }


 }
