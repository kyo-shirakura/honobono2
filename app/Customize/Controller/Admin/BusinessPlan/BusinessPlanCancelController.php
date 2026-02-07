<?php
namespace Customize\Controller\Admin\BusinessPlan;

use Customize\Entity\Master\CancelKind;
use Customize\Form\Type\Master\CancelKindType;
use Customize\Repository\Master\CancelKindRepository;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BusinessPlanCancelController extends AbstractController
{
    /**
     * @var CancelKindRepository
     */
    protected $cancelKindRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;


    /**
     * constructor.
     *
     * @param CancelKindRepository $cancelKindRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        CancelKindRepository $cancelKindRepository,
        ValidatorInterface $validator
    ) {
        $this->cancelKindRepository = $cancelKindRepository;
        $this->validator = $validator;
    }

     /**
      * 一覧画面.
      * @Route("/%eccube_admin_route%/business_plan_cancel", name="admin_business_plan_cancel", methods={"GET", "POST"})
      * @Template("@admin/BusinessPlan/cancel.twig")
      */
      public function index(Request $request, PaginatorInterface $paginator, $page_no = null)
      {
          $CancelKind = $this->cancelKindRepository->findAll();

          return [
              'CancelKind' => $CancelKind,
              'has_errors' => false,
          ];
      }

      /**
       * 編集画面.
       * @Route("/%eccube_admin_route%/business_plan_cancel/new", name="admin_business_plan_cancel_new", methods={"GET", "POST"})
       * @Route("/%eccube_admin_route%/business_plan_cancel/{id}/edit", requirements={"id" = "\d+"}, name="admin_business_plan_cancel_edit", methods={"GET", "POST"})
       * @Template("@admin/BusinessPlan/cancel_edit.twig")
       */
       public function edit(Request $request, $id = null)
       {
           // 編集
           if ($id) {
               $CancelKind = $this->cancelKindRepository->find($id);
           } else {
               $CancelKind = new CancelKind();
           }

           if (is_null($CancelKind)) {
               throw new NotFoundHttpException();
           }

           // 登録フォーム

           $builder = $this->formFactory
               ->createBuilder(CancelKindType::class, $CancelKind);
           $form = $builder->getForm();
           $form->handleRequest($request);

           if ( $form->isSubmitted() && $form->isValid() ) {
               log_info('登録開始', [$CancelKind->getId()]);

               $this->entityManager->persist($CancelKind);
               $this->entityManager->flush();

               log_info('登録完了', [$CancelKind->getId()]);

               $this->addSuccess('admin.common.save_complete', 'admin');
           }

           return [
               'form' => $form->createView(),
               'CancelKind' => $CancelKind,
           ];
       }

}
