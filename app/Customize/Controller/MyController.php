<?php
namespace Customize\Controller;

use Customize\Entity\Business;
use Customize\Entity\Report;
use Customize\Entity\Staff;
use Customize\Form\Type\Front\MyLoginType;
use Customize\Form\Type\Front\StaffReportType;
use Customize\Repository\StaffRepository;
use Customize\Repository\BusinessRepository;
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

class MyController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

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
     * @param StaffRepository $staffRepository
     * @param SafetyConfirmationRepository $safetyConfirmationRepository
     * @param WorkDetailsRepository $workDetailsRepository
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        BusinessRepository $businessRepository,
        CustomerRepository $customerRepository,
        StaffRepository $staffRepository,
        SafetyConfirmationRepository $safetyConfirmationRepository,
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
     * @Route("/my", name="my", methods={"GET"})
     * @Template("My/index.twig")
     */
    public function index()
    {
        return [];
    }

    /**
     * ログイン.
     *
     * @Route("/my/login", name="my_login", methods={"GET", "POST"})
     * @Template("My/login.twig")
     */
     public function login(Request $request, AuthenticationUtils $utils)
     {
         /** @var \Symfony\Component\Form\FormInterface $form */
         $builder = $this->formFactory
             ->createNamedBuilder('', MyLoginType::class);

         $form = $builder->getForm();
         $form->handleRequest($request);
         $login_email = $request->get('login_email');
         $login_phone_number = $request->get('login_phone_number');

         if ( $form->isSubmitted() ) {
             // お客様存在チェック
             $Customer = $this->customerRepository->findOneBy(['email' => $login_email, 'phone_number' => $login_phone_number], ['id' => 'DESC']);
            if( $Customer ){
                $this->session->set('eccube.front.mypage.customer.id', $Customer->getId());
//                return $this->forwardToRoute('staff_report');
                return $this->forwardToRoute('my_menu');
             }else{
                 echo '<font color="red">エラー：入力されたメールアドレス/電話番号は登録されていません</font>';
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
      * @Route("/my/menu", name="my_menu", methods={"GET", "POST"})
      * @Template("My/menu.twig")
      */
     public function menu(Request $request, AuthenticationUtils $utils)
     {
         if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('staff_login');

         // お客様情報取得
         $customer_id = $this->session->get('eccube.front.mypage.customer.id', null);
         $Customer = $this->customerRepository->find($customer_id);
         // スタッフ情報取得
//         $Staff = $this->staffRepository->findOneBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
         // 案件情報取得
         $Business = $this->businessRepository->findBy( array( 'Customer' => $Customer , 'Status' => array(3,4) ), ['id' => 'DESC']);

         return [
             'Customer' => $Customer,
             'Business' => $Business,
         ];

     }

    /**
     * 給与明細.
     *
     * @Route("/my/invoice", name="my_invoice", methods={"GET", "POST"})
     * @Template("My/invoice.twig")
     */
    public function invoice(Request $request, AuthenticationUtils $utils)
    {
        if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('my_login');

        // 顧客情報取得
        $customer_id = $this->session->get('eccube.front.mypage.customer.id', null);
        $Customer = $this->customerRepository->find($customer_id);
        // スタッフ情報取得
//         $Staff = $this->staffRepository->findOneBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
        // 案件情報取得
        $Business = $this->businessRepository->findBy( array( 'Customer' => $Customer , 'Status' => array(3,4) ), ['id' => 'DESC']);

        return [
            'Customer' => $Customer,
            'Business' => $Business,
        ];
    }

    /**
     * 契約情報.
     *
     * @Route("/my/contract", name="my_contract", methods={"GET", "POST"})
     * @Template("My/contract.twig")
     */
    public function contract(Request $request, AuthenticationUtils $utils)
    {
        if(! $this->loginCheck( $request ) ) return $this->redirectToRoute('my_login');

        // 顧客情報取得
        $customer_id = $this->session->get('eccube.front.mypage.customer.id', null);
        $Customer = $this->customerRepository->find($customer_id);
        // スタッフ情報取得
//         $Staff = $this->staffRepository->findOneBy(['phone_number' => $login_phone_number], ['id' => 'DESC']);
        // 案件情報取得
        $Business = $this->businessRepository->findBy( array( 'Customer' => $Customer , 'Status' => array(3,4) ), ['id' => 'DESC']);

        return [
            'Customer' => $Customer,
            'Business' => $Business,
        ];
    }


    // ログインチェック
    public function loginCheck(Request $request = null)
    {
//        $Staff = $this->session->get('eccube.front.mypage.staff', null);
        $customer_id = $this->session->get('eccube.front.mypage.customer.id', null);

        // ログイン済み
//        if( $Staff ) return $Staff;
        if( $customer_id ) return true;

        // ログイン画面未通過
        if( $request == null || $request->get('login_phone_number') == null ){
            return false;
        }
        // ログイン処理
        /** @var \Symfony\Component\Form\FormInterface $form */
        $builder = $this->formFactory
            ->createNamedBuilder('', MyLoginType::class);
        $form = $builder->getForm();
        $form->handleRequest($request);
        $login_phone_number = $request->get('login_phone_number');
//        echo 'login_phone_number: '. $login_phone_number;

        // ログイン経由じゃなければログイン画面に遷移
//        if ( !$form->isSubmitted() ) {
//            return $this->redirectToRoute('staff_login');
//        }
        // スタッフ情報取得
        $Customer = $this->customerRepository->findBy(['email' => $login_email], ['phone_number' => $login_phone_number], ['id' => 'DESC']);
        //session設定
//        $this->session->set('eccube.front.mypage.staff', $Staff);
        $this->session->set('eccube.front.mypage.customer.id', $Customer->getId());

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
