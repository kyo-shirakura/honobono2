<?php

namespace Customize\Controller\Admin\Invoice;

use Customize\Entity\Invoice;
use Customize\Form\Type\Admin\InvoiceType;
use Customize\Repository\InvoiceRepository;
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
use Eccube\Repository\Master\PageMaxRepository;
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

class InvoiceEditController extends AbstractController
{
    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

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
        PageMaxRepository $pageMaxRepository,
        InvoiceRepository $invoiceRepository,
        CustomerRepository $customerRepository,
        BaseInfoRepository $baseInfoRepository,
        MailService $mailService,
        FileUploader $fileUploader,
        MailTemplateRepository $mailTemplateRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->customerRepository = $customerRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->mailService = $mailService;
        $this->fileUploader = $fileUploader;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/invoice/new", name="admin_invoice_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/invoice/{id}/edit", requirements={"id" = "\d+"}, name="admin_invoice_edit", methods={"GET", "POST"})
     * @Template("@admin/Invoice/edit.twig")
     */
     public function index(Request $request, $id = null)
     {
         // 編集
         if ($id) {
             $Invoice = $this->invoiceRepository
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
/*
             return $this->redirectToRoute('admin_invoice_edit', [
                 'id' => $Invoice->getId(),
             ]);
*/
         }

         return [
             'form' => $form->createView(),
             'Invoice' => $Invoice,
         ];
     }
 }
