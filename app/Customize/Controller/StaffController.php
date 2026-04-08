<?php
namespace Customize\Controller;

use Customize\Entity\Business;
use Customize\Entity\Report;
use Customize\Entity\Staff;
use Customize\Form\Type\Admin\SearchReportType;
use Customize\Form\Type\Front\StaffLoginType;
use Customize\Form\Type\Front\StaffReportType;
use Customize\Repository\StaffRepository;
use Customize\Repository\BusinessRepository;
use Customize\Repository\ReportRepository;
use Customize\Repository\Master\SafetyConfirmationRepository;
use Customize\Repository\Master\WorkDetailsRepository;
use Customize\Service\FileUploader;
use Eccube\Controller\AbstractController;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Customer;
use Eccube\Service\MailService;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\MailTemplateRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class StaffController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var BusinessRepository
     */
    protected $businessRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var StaffRepository
     */
    protected $staffRepository;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * @var SafetyConfirmationRepository
     */
    protected $safetyConfirmationRepository;

    /**
     * @var WorkDetailsRepository
     */
    protected $workDetailsRepository;

    /**
     * @var BaseInfo
     */
    private $BaseInfo;

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

    /**
     * StaffController constructor.
     *
     * @param PageMaxRepository $pageMaxRepository
     * @param StaffRepository $staffRepository
     * @param SafetyConfirmationRepository $safetyConfirmationRepository
     * @param ReportRepository $reportRepository
     * @param WorkDetailsRepository $workDetailsRepository
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        BusinessRepository $businessRepository,
        CustomerRepository $customerRepository,
        StaffRepository $staffRepository,
        SafetyConfirmationRepository $safetyConfirmationRepository,
        PageMaxRepository $pageMaxRepository,
        ReportRepository $reportRepository,
        WorkDetailsRepository $workDetailsRepository,
        BaseInfoRepository $baseInfoRepository,
        MailService $mailService,
        FileUploader $fileUploader,
        MailTemplateRepository $mailTemplateRepository
    )
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->businessRepository = $businessRepository;
        $this->customerRepository = $customerRepository;
        $this->staffRepository = $staffRepository;
        $this->safetyConfirmationRepository = $safetyConfirmationRepository;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->reportRepository = $reportRepository;
        $this->workDetailsRepository = $workDetailsRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->mailService = $mailService;
        $this->fileUploader = $fileUploader;
        $this->mailTemplateRepository = $mailTemplateRepository;
//        $this->twig = $twig;
//        $this->eccubeConfig = $eccubeConfig;
    }


    /**
     * TOPページ
     *
     * @Route("/staff", name="staff", methods={"GET"})
     * @Template("Staff/index.twig")
     */
    public function index()
    {
        return [];
    }

    /**
     * ログイン.
     *
     * @Route("/staff/login", name="staff_login", methods={"GET", "POST"})
     * @Template("Staff/login.twig")
     */
     public function login(Request $request, AuthenticationUtils $utils)
     {
/*
         if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
             log_info('認証済のためログイン処理をスキップ');

             return $this->redirectToRoute('staff');
         }
*/
         /** @var \Symfony\Component\Form\FormInterface $form */
         $builder = $this->formFactory
             ->createNamedBuilder('', StaffLoginType::class);

         $form = $builder->getForm();
         $form->handleRequest($request);
         $login_phone_number = $request->get('login_phone_number');

         if ( $form->isSubmitted() ) {
             // スタッフ存在チェック
             $Staff = $this->staffRepository->findBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
            if( $Staff ){
//                return $this->forwardToRoute('staff_report');
                return $this->forwardToRoute('staff_menu');
             }else{
                 echo '<font color="red">エラー：入力された電話番号は登録されていません</font>';
             }
         }

         return [
             'error' => $utils->getLastAuthenticationError(),
             'form' => $form->createView(),
         ];
     }

     /**
      * メニュー.
      *
      * @Route("/staff/menu", name="staff_menu", methods={"GET", "POST"})
      * @Template("Staff/menu.twig")
      */
     public function menu(Request $request, AuthenticationUtils $utils)
     {
         if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('staff_login');

         // スタッフ情報取得
         $staff_id = $this->session->get('eccube.front.mypage.staff.id', null);
         $Staff = $this->staffRepository->find($staff_id);
         // スタッフ情報取得
//         $Staff = $this->staffRepository->findOneBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
         // 案件情報取得
         $Business = $this->businessRepository->findBy( array( 'Staff' => $Staff , 'Status' => array(3,4) ), ['id' => 'DESC']);

         return [
             'Staff' => $Staff,
             'Business' => $Business,
         ];

     }

     /**
      * 業務報告一覧.
      *
      * @Route("/staff/report_list", name="staff_report_list", methods={"GET", "POST"})
      * @Route("/staff/report_list/page/{page_no}", requirements={"page_no" = "\d+"}, name="staff_report_list_page", methods={"GET", "POST"})
      * @Template("Staff/report_list.twig")
      */
     public function report_list(Request $request, PaginatorInterface $paginator, $page_no = null)
     {
         if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('staff_login');

         $builder = $this->formFactory
             ->createBuilder(SearchReportType::class);
         $searchForm = $builder->getForm();
         $searchData = [];

         /**
          * ページの表示件数は, 以下の順に優先される.
          * - リクエストパラメータ
          * - セッション
          * - デフォルト値
          * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
          **/
         $page_count = $this->session->get('eccube.admin.report.search.page_count',
             $this->eccubeConfig->get('eccube_default_page_count'));

         $page_count_param = (int) $request->get('page_count');
         $pageMaxis = $this->pageMaxRepository->findAll();

         if ($page_count_param) {
             foreach ($pageMaxis as $pageMax) {
                 if ($page_count_param == $pageMax->getName()) {
                     $page_count = $pageMax->getName();
                     $this->session->set('eccube.admin.report.search.page_count', $page_count);
                     break;
                 }
             }
         }

         if ('POST' === $request->getMethod()) {
             $searchForm->handleRequest($request);
             $searchData = $searchForm->getData();
         }

         // スタッフ情報取得
         $staff_id = $this->session->get('eccube.front.mypage.staff.id', null);
         $Staff = $this->staffRepository->find($staff_id);
         // 業務報告取得
         $searchData['Staff'] = $Staff;
         if( !isset($searchData['working_ym']) ){
             $searchData['working_ym'] = new \DateTime();
         }
         $qb = $this->reportRepository->getQueryBuilderBySearchData($searchData);
         if( $page_no == null ){
             $page_no = 1;
         }

         $pagination = $paginator->paginate(
             $qb,
             $page_no,
             $page_count
         );

         return [
             'searchForm' => $searchForm->createView(),
             'searchData' => $searchData,
             'pagination' => $pagination,
             'pageMaxis' => $pageMaxis,
             'page_no' => $page_no,
             'page_count' => $page_count,
             'Staff' => $Staff,
         ];

     }

    /**
     * 業務報告.
     *
     * @Route("/staff/report", name="staff_report", methods={"GET", "POST"})
     * @Template("Staff/report.twig")
     */
    public function report(Request $request, AuthenticationUtils $utils)
    {
        if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('staff_login');

        // スタッフ情報取得
        $staff_id = $this->session->get('eccube.front.mypage.staff.id', null);

        $Staff = $this->staffRepository->find($staff_id);
        // 案件情報取得
        $Business = $this->businessRepository->findBy( array( 'Staff' => $Staff , 'Status' => array(3,4) ), ['id' => 'DESC']);

        // 案件登録がなければ終了
        if ( !$Business ) {
            return $this->render("Staff/no_contract.twig", [
                'Staff' => $Staff,
            ]);
        }

        /** @var \Symfony\Component\Form\FormInterface $form */
        $builder = $this->formFactory
            ->createNamedBuilder(''
                    , StaffReportType::class
                    , [ 'staff_id' => $Staff->getId() ]
             );

        $form = $builder->getForm();

        return [
            'error' => $utils->getLastAuthenticationError(),
            'form' => $form->createView(),
            'Staff' => $Staff,
        ];
    }

    /**
     * 業務報告.
     *
     * @Route("/staff/report_complete", name="staff_report_complete", methods={"GET", "POST"})
     * @Template("Staff/report_complete.twig")
     */
    public function complete(Request $request, AuthenticationUtils $utils)
    {
        $Report = new Report();
        $Business = new Business();
        $Customer = new Customer();
        $Staff = new Staff();
        $WorkDetails = $this->workDetailsRepository->findAll();
        $SafetyConfirmation = $this->safetyConfirmationRepository->findAll();

        $business_id = ( isset($_POST["Business"]) ) ? $_POST["Business"] : 1;
        $staff_id = ( isset($_POST["Staff"]) ) ? $_POST["Staff"] : 1;

        $Business = $this->businessRepository->find( $business_id );
        $Staff = $this->staffRepository->find( $staff_id );
        $Customer = $this->customerRepository->find( $Business->getCustomer()->getId() );

        /** @var \Symfony\Component\Form\FormInterface $form */
        $builder = $this->formFactory
            ->createNamedBuilder(''
                    , StaffReportType::class
                    , [ 'staff_id' => $staff_id ]
             );
        $form = $builder->getForm();
        $form->handleRequest($request);

        // ファイルアップロード
        $file = $form["file_receipt"]->getData();
        $filename = null;
        if($file) {
            $filename = $this->fileUploader->upload($file, $staff_id);
            $Report->setPaymentReceipt($filename);
        }

        // Report登録用
        $working_time_start = sprintf( '%02d:%02d', $_POST["working_time_start_hour"], $_POST["working_time_start_minute"] );
        $working_time_end = sprintf( '%02d:%02d', $_POST["working_time_end_hour"], $_POST["working_time_end_minute"] );

        $Report->setBusiness( $Business );
        $Report->setCustomer( $Customer );
        $Report->setStaff( $Staff );

        if( isset( $_POST["working_day"] ) ){
            $Report->setWorkingDay( $_POST["working_day"] );
        }
        $Report->setWorkingTimeStart( $working_time_start );
        $Report->setWorkingTimeEnd( $working_time_end );
        $Report->setWorkingTimeBreak( $_POST["working_time_break"] );
        $Report->setWorkingTimeTotal( $_POST["working_time_total"] );
        if( isset( $_POST["work_other_check"] ) ){
            $Report->setWorkOtherCheck( 1 );
        }
        $Report->setWorkOtherText( $_POST["work_other_text"] );
        $work_details = array();
        if( isset( $_POST["work_details"] ) ){
            $Report->setWorkDetails( $this->conv_encode( $_POST["work_details"] ) );
            foreach( $WorkDetails as $row ) {
                if( array_search( $row->getId(), $_POST["work_details"] ) !== false ){
                    $work_details[] = $row->getName();
                }
            }
        }
        $safety_confirmation = array();
        if( isset( $_POST["safety_confirmation"] ) ){
            $Report->setSafetyConfirmation( $this->conv_encode( $_POST["safety_confirmation"] ) );
            foreach( $SafetyConfirmation as $row ) {
                if( array_search( $row->getId(), $_POST["safety_confirmation"] ) !== false ){
                    $safety_confirmation[] = $row->getName();
                }
            }
        }
        $Report->setPaymentAdvance( $_POST["payment_advance"] );
        if( $filename !== null ){
            $Report->setPaymentReceipt( $filename );
        }
        $Report->setRemark( $_POST["remark"] );

        $this->entityManager->persist($Report);
        $this->entityManager->flush();

        // メール送信
        $this->mailService->sendStaffReportCompleteMail( $Report, $Customer, $Staff, $work_details, $safety_confirmation );

        return [
            'error' => $utils->getLastAuthenticationError(),
            'form' => $form->createView(),
        ];
    }

    /**
     * 給与明細.
     *
     * @Route("/staff/payslip", name="staff_payslip", methods={"GET", "POST"})
     * @Template("Staff/payslip.twig")
     */
    public function payslip(Request $request, AuthenticationUtils $utils)
    {
        if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('staff_login');

        // スタッフ情報取得
        $staff_id = $this->session->get('eccube.front.mypage.staff.id', null);
        $Staff = $this->staffRepository->find($staff_id);
        // スタッフ情報取得
//         $Staff = $this->staffRepository->findOneBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
        // 案件情報取得
        $Business = $this->businessRepository->findBy( array( 'Staff' => $Staff , 'Status' => array(3,4) ), ['id' => 'DESC']);

        return [
            'Staff' => $Staff,
            'Business' => $Business,
        ];
    }


    // ログインチェック
    public function loginCheck(Request $request = null)
    {
//        $Staff = $this->session->get('eccube.front.mypage.staff', null);
        $staff_id = $this->session->get('eccube.front.mypage.staff.id', null);

        // ログイン済み
//        if( $Staff ) return $Staff;
        if( $staff_id ) return true;

        // ログイン画面未通過
        if( $request == null || $request->get('login_phone_number') == null ){
            return false;
        }
        // ログイン処理
        /** @var \Symfony\Component\Form\FormInterface $form */
        $builder = $this->formFactory
            ->createNamedBuilder('', StaffLoginType::class);
        $form = $builder->getForm();
        $form->handleRequest($request);
        $login_phone_number = $request->get('login_phone_number');
//        echo 'login_phone_number: '. $login_phone_number;

        // ログイン経由じゃなければログイン画面に遷移
//        if ( !$form->isSubmitted() ) {
//            return $this->redirectToRoute('staff_login');
//        }
        // スタッフ情報取得
        $Staff = $this->staffRepository->findOneBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
        //session設定
//        $this->session->set('eccube.front.mypage.staff', $Staff);
        $this->session->set('eccube.front.mypage.staff.id', $Staff->getId());

//        return $Staff;
        return true;
    }

    // シリアライズ化
    public function conv_encode($array)
    {
        $result = serialize($array);
        if (is_bool($result)) {
            $result = '';
        }

        return $result;
    }

}
