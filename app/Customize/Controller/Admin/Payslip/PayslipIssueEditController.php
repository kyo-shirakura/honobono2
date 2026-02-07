<?php

namespace Customize\Controller\Admin\Payslip;

use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\BusinessStaff;
use Customize\Entity\PayslipIssueAddItem;
use Customize\Entity\Report;
use Customize\Form\Type\Admin\PayslipIssueType;
use Customize\Form\Type\Admin\PayslipIssueAddItemType;
use Customize\Form\Type\Master\PayslipAddItemsType;
use Customize\Repository\Master\PayslipAddItemsRepository;
use Customize\Repository\BusinessStaffRepository;
use Customize\Repository\PayslipIssueAddItemRepository;
use Customize\Repository\ReportRepository;
use Customize\Repository\StaffRepository;
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

class PayslipIssueEditController extends AbstractController
{
    /**
     * @var PayslipIssueAddItemRepository
     */
    protected $payslipIssueAddItemRepository;

    /**
     * @var BusinessStaffRepository
     */
    protected $businessStaffRepository;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * @var StaffRepository
     */
    protected $staffRepository;

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
        BusinessStaffRepository $businessStaffRepository,
        PayslipIssueAddItemRepository $payslipIssueAddItemRepository,
        ReportRepository $reportRepository,
        StaffRepository $staffRepository,
        BaseInfoRepository $baseInfoRepository,
        MailService $mailService,
        FileUploader $fileUploader,
        MailTemplateRepository $mailTemplateRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->businessStaffRepository = $businessStaffRepository;
        $this->payslipIssueAddItemRepository = $payslipIssueAddItemRepository;
        $this->reportRepository = $reportRepository;
        $this->staffRepository = $staffRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->mailService = $mailService;
        $this->fileUploader = $fileUploader;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/payslip/{id}/issue/edit/{issue_ym}", requirements={"id" = "\d+"}, name="admin_payslip_issue_edit", methods={"GET", "POST"})
     * @Template("@admin/Payslip/issue_edit.twig")
     */
     public function index(Request $request, $id = null, $issue_ym = null)
     {
         if( $issue_ym == null ) $issue_ym = date('Y-m', strtotime('-1 month'));
//         echo '<br>issue_ym: '. $issue_ym;

         $config = $this->eccubeConfig;
         $Payslip = new PayslipIssueAddItem();
         if ($id) {
             $Staff = $this->staffRepository->find($id);
             $BusinessStaff = $this->businessStaffRepository->getBusinessStaffByThisMonth([
                     'Staff' => $Staff,
                     'working_ym' => $issue_ym,
             ]);
             $Report = $this->reportRepository->findBy(
                     ['Staff' => $Staff ],
//                     ['working_day' => '2025-05-01' ],
                 );
             $Report = $this->reportRepository->getMonthlyReportByStaff([
                     'Staff' => $Staff,
                     'working_ym' => $issue_ym,
             ]);

             if (is_null($Staff)) {
                 throw new NotFoundHttpException();
             }
         }else{
             $BusinessStaff = new BusinessStaff();
             $Report = new Report();
         }

/*
         // 編集
         if ($id) {
             $Payslip = $this->payslipIssueAddItemRepository
                 ->find($id);

             if (is_null($Payslip)) {
                 throw new NotFoundHttpException();
             }
         // 新規登録
         } else {
             $Payslip = new Payslip();
             $Payslip->setRevision(0);
             if( isset( $_GET['sid'] ) && $_GET['sid'] != '' ){
                 $Payslip->setStaff( $this->staffRepository->find( $_GET['sid'] ) );
             }
         }
*/
         // 給与明細登録フォーム
         $builder = $this->formFactory
             ->createBuilder(PayslipIssueType::class, $Payslip);

         $event = new EventArgs(
             [
                 'builder' => $builder,
                 'Staff' => $Staff,
                 'Payslip' => $Payslip,
             ],
             $request
         );

         $form = $builder->getForm();
         $form->handleRequest($request);
/*
         if ( $form->isSubmitted() && $form->isValid() && isset($_POST["admin_search_payslip"]["Staff"]) ) {
             log_info('給与明細登録開始', [$Payslip->getId()]);

            $Staff = $this->staffRepository->find( $Payslip->getStaff()->getId() );
            $Payslip->setIssueYm( $Payslip->getIssueYm()->modify('+1 day') );
            $issue_ym = $Payslip->getIssueYm()->format("Ym");

            // ファイルアップロード
            $file = $form["file"]->getData();
            $filename = null;
            if($file) {
                $filename = $this->fileUploader->payslipUpload( $file, $issue_ym, $Staff->getId() );
            }
             if( $filename !== null ){
                 $Payslip->setPdfFile( $filename );
             }

             $this->entityManager->persist($Payslip);
             $this->entityManager->flush();

             // メール送信
             if( $Payslip->getSendmail() ){
                 $this->mailService->sendPayslipMail( $Payslip, $Staff );
             }

             log_info('給与明細登録完了', [$Payslip->getId()]);

             $this->addSuccess('admin.common.save_complete', 'admin');

             return $this->redirectToRoute('admin_payslip_edit', [
                 'id' => $Payslip->getId(),
             ]);

         }
*/

         return [
             'form' => $form->createView(),
             'Staff' => $Staff,
             'BusinessStaff' => $BusinessStaff,
             'Payslip' => $Payslip,
             'Report' => $Report,
             'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
             'AdminReportFormToken' => $config['admin_report_form_token'],
//             'AdminReportFormToken' => 'ADMIN_REPORT_FORM_TOKEN',
         ];
     }
 }
