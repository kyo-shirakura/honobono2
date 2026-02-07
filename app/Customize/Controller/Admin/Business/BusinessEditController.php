<?php

namespace Customize\Controller\Admin\Business;

use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\Business;
use Customize\Form\Type\Admin\BusinessType;
use Customize\Repository\BusinessRepository;
use Customize\Repository\BusinessStaffRepository;
use Customize\Repository\StaffRepository;
use Customize\Repository\Master\BusinessStatusRepository;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Util\StringUtil;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BusinessEditController extends AbstractController
{
    /**
     * @var BusinessRepository
     */
    protected $businessRepository;

    /**
     * @var BusinessStaffRepository
     */
    protected $businessStaffRepository;

    /**
     * @var BusinessStatusRepository
     */
    protected $businessStatusRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var StaffRepository
     */
    protected $staffRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;


    public function __construct(
        PageMaxRepository $pageMaxRepository,
        BusinessRepository $businessRepository,
        BusinessStaffRepository $businessStaffRepository,
        BusinessStatusRepository $businessStatusRepository,
        CustomerRepository $customerRepository,
        StaffRepository $staffRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->businessRepository = $businessRepository;
        $this->businessStaffRepository = $businessStaffRepository;
        $this->businessStatusRepository = $businessStatusRepository;
        $this->customerRepository = $customerRepository;
        $this->staffRepository = $staffRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/business/new", name="admin_business_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/business/{id}/edit", requirements={"id" = "\d+"}, name="admin_business_edit", methods={"GET", "POST"})
     * @Template("@admin/Business/edit.twig")
     */
     public function index(Request $request, PaginatorInterface $paginator, $id = null)
     {
         //$this->entityManager->getFilters()->enable('incomplete_business_status_hidden');
         // 編集
         if ($id) {
             $Business = $this->businessRepository->find($id);

             $qb = $this->businessStaffRepository->getQueryBuilderByBusinessID($Business);
             $pagination = $paginator->paginate(
                 $qb,
                 1,
                 100
             );

             if (is_null($Business)) {
                 throw new NotFoundHttpException();
             }
         // 新規登録
         } else {
             //$Business = $this->businessRepository->newBusiness();
             $status_id = ( isset($_POST["Status"]) ) ? $_POST["Status"] : 0;
             $customer_id = ( isset($_POST["Customer"]) ) ? $_POST["Customer"] : 0;
             $staff_id = ( isset($_POST["Staff"]) ) ? $_POST["Staff"] : 0;

             $Status = $this->businessStatusRepository->find( $status_id );
             $Customer = $this->customerRepository->find( $customer_id );
             $Staff = $this->staffRepository->find( $staff_id );

             $Business = new Business();
             $pagination = [];
         }

         // 案件登録フォーム
         $builder = $this->formFactory
             ->createBuilder(BusinessType::class, $Business);

         $form = $builder->getForm();

         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             log_info('登録開始', [$Business->getId()]);

             $this->entityManager->persist($Business);
             $this->entityManager->flush();

             log_info('登録完了', [$Business->getId()]);

             $this->addSuccess('admin.common.save_complete', 'admin');

//admin_business_staff_new


             return $this->redirectToRoute('admin_business_edit', [
                 'id' => $Business->getId(),
             ]);
         }

         $event = new EventArgs(
             [
                 'builder' => $builder,
                 'Business' => $Business,
                 'pagination' => $pagination,
             ],
             $request
         );
//         $this->eventDispatcher->dispatch($event, EccubeEvents::ADMIN_BUSINESS_EDIT_INDEX_INITIALIZE);

         $form = $builder->getForm();
/*
         $form->handleRequest($request);
         $page_count = (int) $this->session->get('eccube.admin.business_edit.business.page_count',
             $this->eccubeConfig->get('eccube_default_page_count'));

         $page_count_param = (int) $request->get('page_count');
         $pageMaxis = $this->pageMaxRepository->findAll();

         if ($page_count_param) {
             foreach ($pageMaxis as $pageMax) {
                 if ($page_count_param == $pageMax->getName()) {
                     $page_count = $pageMax->getName();
                     $this->session->set('eccube.admin.business_edit.business.page_count', $page_count);
                     break;
                 }
             }
         }
         $page_no = (int) $request->get('page_no', 1);
         $qb = $this->businessRepository->getQueryBuilderByBusiness($Business);
         $pagination = [];
         if (!is_null($Business->getId())) {
             $pagination = $paginator->paginate(
                 $qb,
                 $page_no > 0 ? $page_no : 1,
                 $page_count
             );
         }
*/
/*
         if ($form->isSubmitted() && $form->isValid()) {
             log_info('案件登録開始', [$Business->getId()]);

             $this->entityManager->persist($Business);
             $this->entityManager->flush();

             log_info('案件登録完了', [$Business->getId()]);

             $event = new EventArgs(
                 [
                     'form' => $form,
                     'Business' => $Business,
                 ],
                 $request
             );
             $this->eventDispatcher->dispatch($event, EccubeEvents::ADMIN_BUSINESS_EDIT_INDEX_COMPLETE);

             $this->addSuccess('admin.common.save_complete', 'admin');

             return $this->redirectToRoute('admin_business_edit', [
                 'id' => $Business->getId(),
             ]);
         }
*/
         return [
             'form' => $form->createView(),
             'Business' => $Business,
             'pagination' => $pagination,
             'BusinessConstractors' => BusinessConfig::BUSINESS_CONTRACTOR,
             'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
             'BusinessRegularType' => BusinessConfig::BUSINESS_REGULAR_TYPE,
             'BusinessWeekly' => BusinessConfig::BUSINESS_WEEKLY,
//             'pageMaxis' => $pageMaxis,
//             'page_no' => $page_no,
//             'page_count' => $page_count,
         ];
     }
 }
