<?php

namespace Customize\Controller\Admin\Report;

use Customize\Entity\Report;
use Customize\Form\Type\Admin\ReportType;
use Customize\Repository\ReportRepository;
use Customize\Repository\Master\SafetyConfirmationRepository;
use Customize\Repository\Master\WorkDetailsRepository;
use Customize\Repository\Master\ServicesRepository;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Util\StringUtil;
use Eccube\Repository\Master\PageMaxRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReportEditController extends AbstractController
{
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
     * @var ServicesRepository
     */
    protected $servicesRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;


    public function __construct(
        PageMaxRepository $pageMaxRepository,
        ReportRepository $reportRepository,
        SafetyConfirmationRepository $safetyConfirmationRepository,
        WorkDetailsRepository $workDetailsRepository,
        ServicesRepository $servicesRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->reportRepository = $reportRepository;
        $this->safetyConfirmationRepository = $safetyConfirmationRepository;
        $this->workDetailsRepository = $workDetailsRepository;
        $this->servicesRepository = $servicesRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/report/new", name="admin_report_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/report/{id}/edit", requirements={"id" = "\d+"}, name="admin_report_edit", methods={"GET", "POST"})
     * @Template("@admin/Report/edit.twig")
     */
    public function index(Request $request, $id = null)
    {
        //$this->entityManager->getFilters()->enable('incomplete_report_status_hidden');
        $work_details = array();
        $safety_confirmation = array();
        // 編集
        if ($id) {
            $Report = $this->reportRepository->find($id);
            if (is_null($Report)) {
                throw new NotFoundHttpException();
            }
///            echo '<br>Report->getWorkDetails()';
///            var_dump( $Report->getWorkDetails() );
            if( $Report->getWorkDetails() ){
                $work_details = $this->conv_decode( $Report->getWorkDetails() );
                $WD = $this->workDetailsRepository->findBy( ['id' => $work_details] );
                $Report->setWorkDetails( $WD );
            }
///            echo '<br>Report->getSafetyConfirmation()';
            if( $Report->getSafetyConfirmation() ){
                $safety_confirmation = $this->conv_decode( $Report->getSafetyConfirmation() );
                $SC = $this->safetyConfirmationRepository->findBy( ['id' => $safety_confirmation] );
                $Report->setSafetyConfirmation( $SC );
            }

         // 新規登録
         } else {
             //$Report = $this->reportRepository->newReport();
             $Report = new Report();
         }

         $builder = $this->formFactory
             ->createBuilder(ReportType::class, $Report);
         $form = $builder->getForm();
         $form->handleRequest($request);

//         echo '<br>-----------<br>';
//         var_dump($Report->getSafetyConfirmation());
//         echo '<br>-----------<br>';

/*
         $form->handleRequest($request);
         $page_count = (int) $this->session->get('eccube.admin.report_edit.report.page_count',
             $this->eccubeConfig->get('eccube_default_page_count'));

         $page_count_param = (int) $request->get('page_count');
         $pageMaxis = $this->pageMaxRepository->findAll();

         if ($page_count_param) {
             foreach ($pageMaxis as $pageMax) {
                 if ($page_count_param == $pageMax->getName()) {
                     $page_count = $pageMax->getName();
                     $this->session->set('eccube.admin.report_edit.report.page_count', $page_count);
                     break;
                 }
             }
         }
         $page_no = (int) $request->get('page_no', 1);
         $qb = $this->reportRepository->getQueryBuilderByReport($Report);
         $pagination = [];
         if (!is_null($Report->getId())) {
             $pagination = $paginator->paginate(
                 $qb,
                 $page_no > 0 ? $page_no : 1,
                 $page_count
             );
         }
*/
         if ($form->isSubmitted() && $form->isValid()) {
             log_info('案件登録開始', [$Report->getId()]);


//             $Report->setWorkDetails( null );
//             $Report->setSafetyConfirmation( null );
//             echo '<br>Report->getWorkDetails()<br>';
             var_dump( $Report->getWorkDetails() );
             if( $Report->getWorkDetails() ){
                 $Report->setWorkDetails( $this->conv_encode( $Report->getWorkDetails() ) );
             }
//             echo '<br>conv_encode<br>';
             var_dump( $Report->getWorkDetails() );

 ///            echo '<br>Report->getSafetyConfirmation()';
             if( $Report->getSafetyConfirmation() ){
                 $Report->setSafetyConfirmation( $this->conv_encode( $Report->getSafetyConfirmation() ) );
             }

             $this->entityManager->persist($Report);
             $this->entityManager->flush();

             log_info('案件登録完了', [$Report->getId()]);

             $event = new EventArgs(
                 [
                     'form' => $form,
                     'Report' => $Report,
                 ],
                 $request
             );
//             $this->eventDispatcher->dispatch($event, EccubeEvents::ADMIN_BUSINESS_EDIT_INDEX_COMPLETE);

             $this->addSuccess('admin.common.save_complete', 'admin');

             return $this->redirectToRoute('admin_report_edit', [
                 'id' => $Report->getId(),
             ]);
         }

         $Services = $this->servicesRepository->findAll();
         $Safeties = $this->safetyConfirmationRepository->findAll();

         return [
             'form' => $form->createView(),
             'Report' => $Report,
             'Services' => $Services,
             'Safeties' => $Safeties,
             'work_details' => $work_details,
             'safety_confirmation' => $safety_confirmation,
//             'pagination' => $pagination,
//             'pageMaxis' => $pageMaxis,
//             'page_no' => $page_no,
//             'page_count' => $page_count,
         ];
     }

     /**
      * @Route("/%eccube_admin_route%/report/trash/{id}", requirements={"id" = "\d+"}, name="admin_report_trash", methods={"GET", "POST", "PUT"})
      */
     public function trash(Request $request, $id = null)
     {
         if ($id) {
             $Report = $this->reportRepository->find($id);
             if (is_null($Report)) {
                 throw new NotFoundHttpException();
             }
             $Report->setVisible(0);

             $this->entityManager->persist($Report);
             $this->entityManager->flush();
         }

         return $this->redirectToRoute('admin_report');
     }

     // シリアライズ化
     public function conv_encode($data)
     {
         $ids = array();
         foreach($data as $row){
             $ids[] = $row->getId();
         }

         $result = serialize($ids);
         if (is_bool($result)) {
             $result = '';
         }

         return $result;
     }
     // 非シリアライズ化
     public function conv_decode($data)
     {
         $result = unserialize($data);
         if (is_bool($result)) {
             $result = '';
         }

         return $result;
     }

 }
