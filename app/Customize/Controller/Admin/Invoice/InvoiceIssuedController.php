<?php
namespace Customize\Controller\Admin\Invoice;

use Customize\Entity\Master\BusinessConfig;
use Customize\Repository\InvoiceIssuedRepository;
use Customize\Form\Type\Admin\SearchInvoiceIssuedType;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Customer;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Service\MailService;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InvoiceIssuedController extends AbstractController
{
    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;

    /**
     * @var CsvExportExpansionService
     */
    protected $csvExportExpansionService;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var InvoiceIssuedRepository
     */
    protected $invoiceIssuedRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * constructor.
     *
     * @param CustomerRepository $customerRepository
     * @param PurchaseFlow $orderPurchaseFlow
     * @param PageMaxRepository $pageMaxRepository
     * @param InvoiceIssuedRepository $invoiceIssuedRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        CustomerRepository $customerRepository,
        PurchaseFlow $orderPurchaseFlow,
        PageMaxRepository $pageMaxRepository,
        InvoiceIssuedRepository $invoiceIssuedRepository,
        ValidatorInterface $validator
    ) {
        $this->customerRepository = $customerRepository;
        $this->purchaseFlow = $orderPurchaseFlow;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->invoiceIssuedRepository = $invoiceIssuedRepository;
        $this->validator = $validator;
    }

     /**
      * 一覧画面.
      * @Route("/%eccube_admin_route%/invoice/issued", name="admin_invoice_issued", methods={"GET", "POST"})
      * @Route("/%eccube_admin_route%/invoice/issued/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_invoice_page", methods={"GET", "POST"})
      * @Template("@admin/Invoice/issued.twig")
      */
      public function index(Request $request, PaginatorInterface $paginator, $page_no = null)
      {
          $builder = $this->formFactory
              ->createBuilder(SearchInvoiceIssuedType::class);
          $searchForm = $builder->getForm();
          $firstAccess = 0;
          // 顧客一覧の取得
          $Customers = $this->customerRepository->findAll();
          $searchCustomerID = '';

          /**
           * ページの表示件数は, 以下の順に優先される.
           * - リクエストパラメータ
           * - セッション
           * - デフォルト値
           * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
           **/
          $page_count = $this->session->get('eccube.admin.invoice.search.page_count',
              $this->eccubeConfig->get('eccube_default_page_count'));

          $page_count_param = (int) $request->get('page_count');
          $pageMaxis = $this->pageMaxRepository->findAll();

          if ($page_count_param) {
              foreach ($pageMaxis as $pageMax) {
                  if ($page_count_param == $pageMax->getName()) {
                      $page_count = $pageMax->getName();
                      $this->session->set('eccube.admin.invoice.search.page_count', $page_count);
                      break;
                  }
              }
          }

          if ('POST' === $request->getMethod()) {
              $searchForm->handleRequest($request);

              if ( isset( $_POST['admin_search_invoice']['Customer'] ) || isset( $_POST['admin_search_invoice']['issue_ym'] ) ) {
                  /**
                   * 検索が実行された場合は, セッションに検索条件を保存する.
                   * ページ番号は最初のページ番号に初期化する.
                   */
                  $page_no = 1;
                  $searchData = $searchForm->getData();

                  if( isset( $_POST['admin_search_invoice']['Customer'] ) && $_POST['admin_search_invoice']['Customer'] != '' ){
                      $Customer = $this->customerRepository->find( $_POST['admin_search_invoice']['Customer'] );
                      $searchData['Customer'] = $Customer;
                      $searchCustomerID = $_POST['admin_search_invoice']['Customer'];
                  }
                  if( isset( $_POST['admin_search_invoice']['issue_ym'] ) && $_POST['admin_search_invoice']['issue_ym'] != '' ){
                      $searchData['issue_ym'] = new \DateTime( $_POST['admin_search_invoice']['issue_ym']. '-01' );
                  }

                  // 検索条件, ページ番号をセッションに保持.
                  $this->session->set('eccube.admin.invoice.search', FormUtil::getViewData($searchForm));
                  $this->session->set('eccube.admin.invoice.search.page_no', $page_no);
              } else {
                  // 検索エラーの際は, 詳細検索枠を開いてエラー表示する.
                  return [
                      'searchForm' => $searchForm->createView(),
                      'searchData' => [],
                      'pagination' => [],
                      'pageMaxis' => $pageMaxis,
                      'page_no' => $page_no,
                      'page_count' => $page_count,
                      'has_errors' => true,
                      'firstAccess' => 1,
                      'Customers' => $Customers,
                      'searchCustomerID' => $searchCustomerID
                  ];
              }
          } else {

              if (null !== $page_no || $request->get('resume')) {
                  /*
                   * ページ送りの場合または、他画面から戻ってきた場合は, セッションから検索条件を復旧する.
                   */
                  if ($page_no) {
                      // ページ送りで遷移した場合.
                      $this->session->set('eccube.admin.invoice.search.page_no', (int) $page_no);
                  } else {
                      // 他画面から遷移した場合.
                      $page_no = $this->session->get('eccube.admin.invoice.search.page_no', 1);
                  }
                  $viewData = $this->session->get('eccube.admin.invoice.search', []);
                  $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
              } else {
                  /**
                   * 初期表示の場合.
                   */
                  $page_no = 1;
                  $viewData = [];
                  $firstAccess = 1;

                  if ($statusId = (int) $request->get('order_status_id')) {
                      $viewData = ['status' => $statusId];
                  }

                  $searchData = FormUtil::submitAndGetData($searchForm, $viewData);

                  // セッション中の検索条件, ページ番号を初期化.
                  $this->session->set('eccube.admin.invoice.search', $viewData);
                  $this->session->set('eccube.admin.invoice.search.page_no', $page_no);
              }
          }
          $qb = $this->invoiceIssuedRepository->getQueryBuilderByIssuedData($searchData);

          $event = new EventArgs(
              [
                  'qb' => $qb,
                  'searchData' => $searchData,
              ],
              $request
          );

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
              'has_errors' => false,
              'firstAccess' => $firstAccess,
              'Customers' => $Customers,
              'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
              'PaymentType' => BusinessConfig::BUSINESS_PAYMENT,
              'searchCustomerID' => $searchCustomerID
  //            'OrderStatuses' => $this->orderStatusRepository->findBy([], ['sort_no' => 'ASC']),
          ];
      }
}
