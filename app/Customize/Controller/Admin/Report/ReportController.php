<?php
namespace Customize\Controller\Admin\Report;

use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Service\MailService;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Util\FormUtil;
use Customize\Repository\ReportRepository;
use Customize\Form\Type\Admin\SearchReportType;
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

class ReportController extends AbstractController
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
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

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
     * @param PurchaseFlow $orderPurchaseFlow
     * @param PageMaxRepository $pageMaxRepository
     * @param ReportRepository $reportRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        PurchaseFlow $orderPurchaseFlow,
        PageMaxRepository $pageMaxRepository,
        ReportRepository $reportRepository,
        ValidatorInterface $validator
    ) {
        $this->purchaseFlow = $orderPurchaseFlow;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->reportRepository = $reportRepository;
        $this->validator = $validator;
    }

     /**
      * 一覧画面.
      * @Route("/%eccube_admin_route%/report", name="admin_report", methods={"GET", "POST"})
      * @Route("/%eccube_admin_route%/report/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_report_page", methods={"GET", "POST"})
      * @Template("@admin/Report/index.twig")
      */
      public function index(Request $request, PaginatorInterface $paginator, $page_no = null)
      {
          $builder = $this->formFactory
              ->createBuilder(SearchReportType::class);
          $searchForm = $builder->getForm();
          $firstAccess = 0;

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
//echo '<br>1';
              $searchForm->handleRequest($request);
              $searchData = $searchForm->getData();

              if ($searchForm->isValid()) {
                  /**
                   * 検索が実行された場合は, セッションに検索条件を保存する.
                   * ページ番号は最初のページ番号に初期化する.
                   */
                  $page_no = 1;
                  $searchData = $searchForm->getData();

                  // 検索条件, ページ番号をセッションに保持.
                  $this->session->set('eccube.admin.report.search', FormUtil::getViewData($searchForm));
                  $this->session->set('eccube.admin.report.search.page_no', $page_no);
              } else {
                  // 検索エラーの際は, 詳細検索枠を開いてエラー表示する.
                  return [
                      'searchForm' => $searchForm->createView(),
                      'searchData' => [],
                      'pagination' => [],
                      'pageMaxis' => $pageMaxis,
                      'page_no' => $page_no,
                      'page_count' => $page_count,
//                      'has_errors' => true,
                      'firstAccess' => 1,
                  ];
              }
          } else {
              if (null !== $page_no || $request->get('resume')) {
                  /*
                   * ページ送りの場合または、他画面から戻ってきた場合は, セッションから検索条件を復旧する.
                   */
                  if ($page_no) {
                      // ページ送りで遷移した場合.
                      $this->session->set('eccube.admin.report.search.page_no', (int) $page_no);
                  } else {
                      // 他画面から遷移した場合.
                      $page_no = $this->session->get('eccube.admin.report.search.page_no', 1);
                  }
                  $viewData = $this->session->get('eccube.admin.report.search', []);
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

                  // 件数が多いので初期設定
                  $searchData['status'] = array('on');
                  $searchData['job_pref'] = null;

                  $last_modified_date = new \DateTime();
                  $last_modified_date->modify('-7 day');
                  $searchData['last_modified_date'] = $last_modified_date->format('Y-m-d');

                  // セッション中の検索条件, ページ番号を初期化.
                  $this->session->set('eccube.admin.report.search', $viewData);
                  $this->session->set('eccube.admin.report.search.page_no', $page_no);
              }
          }

          $qb = $this->reportRepository->getQueryBuilderBySearchData($searchData);

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
  //            'OrderStatuses' => $this->orderStatusRepository->findBy([], ['sort_no' => 'ASC']),
          ];
      }

}
