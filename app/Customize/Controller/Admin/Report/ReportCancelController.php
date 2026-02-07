<?php

namespace Customize\Controller\Admin\Report;

use Customize\Entity\Report;
use Customize\Entity\Master\CancelKind;
use Customize\Form\Type\Admin\ReportCancelType;
use Customize\Repository\ReportRepository;
use Customize\Repository\Master\CancelKindRepository;
use Customize\Repository\Master\WorkDetailsRepository;
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

class ReportCancelController extends AbstractController
{
    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * @var CancelKindRepository
     */
    protected $cancelKindRepository;

    public function __construct(
        CancelKindRepository $cancelKindRepository,
        ReportRepository $reportRepository,
        ValidatorInterface $validator
    ) {
        $this->cancelKindRepository = $cancelKindRepository;
        $this->reportRepository = $reportRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/report/cancel", name="admin_report_cancel", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/report/cancel/{id}/edit", requirements={"id" = "\d+"}, name="admin_report_cancel_edit", methods={"GET", "POST"})
     * @Template("@admin/Report/cancel.twig")
     */
    public function index(Request $request, $id = null)
    {
        $work_details = array();
        $safety_confirmation = array();
        // 編集
        if ($id) {
            $Report = $this->reportRepository->find($id);
            if (is_null($Report)) {
                throw new NotFoundHttpException();
            }
         // 新規登録
         } else {
             $Report = new Report();
         }

         $builder = $this->formFactory
             ->createBuilder(ReportCancelType::class, $Report);
         $form = $builder->getForm();
         $form->handleRequest($request);
         if ($form->isSubmitted()) {
             log_info('案件登録開始', [$Report->getId()]);

             $this->entityManager->persist($Report);
             $this->entityManager->flush();

             log_info('案件登録完了', [$Report->getId()]);

             $this->addSuccess('admin.common.save_complete', 'admin');

/*
             return $this->redirectToRoute('admin_report_cancel_edit', [
                 'id' => $Report->getId(),
             ]);
*/
        }

         $CancelKind = $this->cancelKindRepository->findAll();

         return [
             'form' => $form->createView(),
             'Report' => $Report,
             'CancelKind' => $CancelKind,
         ];
     }
 }
