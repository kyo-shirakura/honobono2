<?php
namespace Customize\Controller\Admin\BusinessPlan;

use Customize\Entity\BusinessPlan;
use Customize\Form\Type\Admin\BusinessPlanType;
use Customize\Repository\BusinessPlanRepository;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BusinessPlanEditController extends AbstractController
{
    /**
     * @var BusinessPlanRepository
     */
    protected $businessPlanRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * constructor.
     *
     * @param BusinessPlanRepository $businessPlanRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        BusinessPlanRepository $businessPlanRepository,
        ValidatorInterface $validator
    ) {
        $this->businessPlanRepository = $businessPlanRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/business_plan/new", name="admin_business_plan_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/business_plan/{id}/edit", requirements={"id" = "\d+"}, name="admin_business_plan_edit", methods={"GET", "POST"})
     * @Template("@admin/BusinessPlan/edit.twig")
     */
     public function index(Request $request, $id = null)
     {
          // 編集
          if ($id) {
              $Config = $this->businessPlanRepository
                  ->find($id);
          } else {
              $Config = new BusinessPlan();
          }

          if (is_null($Config)) {
              throw new NotFoundHttpException();
          }

          // 登録フォーム
          $builder = $this->formFactory
              ->createBuilder(BusinessPlanType::class, $Config);

          $event = new EventArgs(
              [
                  'builder' => $builder,
                  'Config' => $Config,
              ],
              $request
          );

          $form = $builder->getForm();
          $form->handleRequest($request);

          if ( $form->isSubmitted() && $form->isValid() ) {
              log_info('登録開始', [$Config->getId()]);

              $this->entityManager->persist($Config);
              $this->entityManager->flush();

              log_info('登録完了', [$Config->getId()]);

              $this->addSuccess('admin.common.save_complete', 'admin');
          }

          return [
              'form' => $form->createView(),
              'Config' => $Config,
          ];
      }
  }
