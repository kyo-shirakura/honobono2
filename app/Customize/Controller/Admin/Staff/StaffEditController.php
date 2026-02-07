<?php

namespace Customize\Controller\Admin\Staff;

use Customize\Entity\Staff;
use Customize\Form\Type\Admin\StaffType;
use Customize\Repository\StaffRepository;
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

class StaffEditController extends AbstractController
{
    /**
     * @var StaffRepository
     */
    protected $staffRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * constructor.
     *
     * @param PageMaxRepository $pageMaxRepository
     * @param StaffRepository $staffRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        PageMaxRepository $pageMaxRepository,
        StaffRepository $staffRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->staffRepository = $staffRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/staff/new", name="admin_staff_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/staff/{id}/edit", requirements={"id" = "\d+"}, name="admin_staff_edit", methods={"GET", "POST"})
     * @Template("@admin/Staff/edit.twig")
     */
     public function index(Request $request, $id = null)
     {
         //$this->entityManager->getFilters()->enable('incomplete_staff_status_hidden');
         // 編集
         if ($id) {
             $Staff = $this->staffRepository->find($id);

             if (is_null($Staff)) {
                 throw new NotFoundHttpException();
             }
         // 新規登録
         } else {
             //$Staff = $this->staffRepository->newStaff();
             $Staff = new Staff();
         }

         // スタッフ登録フォーム
         $builder = $this->formFactory
             ->createBuilder(StaffType::class, $Staff);

         $event = new EventArgs(
             [
                 'builder' => $builder,
                 'Staff' => $Staff,
             ],
             $request
         );

         $form = $builder->getForm();
         $form->handleRequest($request);
/*
if ($form->isSubmitted() && $form->isValid()) {
    log_info('登録開始', [$Staff->getId()]);

    $this->entityManager->persist($Staff);
    $this->entityManager->flush();

    log_info('登録完了', [$Staff->getId()]);

//             $this->addSuccess('admin.common.save_complete', 'admin');

    return $this->redirectToRoute('admin_staff_edit', [
        'id' => $Staff->getId(),
    ]);
}

         $form->handleRequest($request);
         $page_count = (int) $this->session->get('eccube.admin.staff_edit.staff.page_count',
             $this->eccubeConfig->get('eccube_default_page_count'));

         $page_count_param = (int) $request->get('page_count');
         $pageMaxis = $this->pageMaxRepository->findAll();

         if ($page_count_param) {
             foreach ($pageMaxis as $pageMax) {
                 if ($page_count_param == $pageMax->getName()) {
                     $page_count = $pageMax->getName();
                     $this->session->set('eccube.admin.staff_edit.staff.page_count', $page_count);
                     break;
                 }
             }
         }
         $page_no = (int) $request->get('page_no', 1);
         $qb = $this->staffRepository->getQueryBuilderByStaff($Staff);
         $pagination = [];
         if (!is_null($Staff->getId())) {
             $pagination = $paginator->paginate(
                 $qb,
                 $page_no > 0 ? $page_no : 1,
                 $page_count
             );
         }
*/
         if ($form->isSubmitted() && $form->isValid()) {
             log_info('スタッフ登録開始', [$Staff->getId()]);

//             if ($Staff->getPlainPassword() !== $this->eccubeConfig['eccube_default_password']) {
//                 $password = $this->passwordHasher->hashPassword($Staff, $Staff->getPlainPassword());
//                 $Staff->setPassword($password);
//             }

             // 退会ステータスに更新の場合、ダミーのアドレスに更新
//             $newStatusId = $Staff->getStatus()->getId();
//             if ($oldStatusId != $newStatusId && $newStatusId == StaffStatus::WITHDRAWING) {
//                 $Staff->setEmail(StringUtil::random(60).'@dummy.dummy');
//             }

             $this->entityManager->persist($Staff);
             $this->entityManager->flush();

             log_info('スタッフ登録完了', [$Staff->getId()]);

             $event = new EventArgs(
                 [
                     'form' => $form,
                     'Staff' => $Staff,
                 ],
                 $request
             );

             $this->addSuccess('admin.common.save_complete', 'admin');

             return $this->redirectToRoute('admin_staff_edit', [
                 'id' => $Staff->getId(),
             ]);
         }

         return [
             'form' => $form->createView(),
             'Staff' => $Staff,
//             'pagination' => $pagination,
//             'pageMaxis' => $pageMaxis,
//             'page_no' => $page_no,
//             'page_count' => $page_count,
         ];
     }
 }
