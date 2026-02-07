<?php

namespace Customize\Controller\Admin\Business;

use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\Business;
use Customize\Entity\BusinessStaff;
use Customize\Entity\BusinessStaffWorkingTime;
use Customize\Form\Type\Admin\BusinessStaffType;
use Customize\Repository\BusinessRepository;
use Customize\Repository\BusinessStaffRepository;
use Customize\Repository\BusinessStaffWorkingTimeRepository;
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

class BusinessStaffEditController extends AbstractController
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
     * @var BusinessStaffWorkingTimeRepository
     */
    protected $businessStaffWorkingTimeRepository;

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
        BusinessStaffWorkingTimeRepository $businessStaffWorkingTimeRepository,
        BusinessStatusRepository $businessStatusRepository,
        CustomerRepository $customerRepository,
        StaffRepository $staffRepository,
        ValidatorInterface $validator
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->businessRepository = $businessRepository;
        $this->businessStaffRepository = $businessStaffRepository;
        $this->businessStaffWorkingTimeRepository = $businessStaffWorkingTimeRepository;
        $this->businessStatusRepository = $businessStatusRepository;
        $this->customerRepository = $customerRepository;
        $this->staffRepository = $staffRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/%eccube_admin_route%/business/{bid}/staff/new", name="admin_business_staff_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/business/{bid}/staff/{id}/edit", requirements={"id" = "\d+"}, name="admin_business_staff_edit", methods={"GET", "POST"})
     * @Template("@admin/Business/editstaff.twig")
     */
     public function index(Request $request, PaginatorInterface $paginator, $bid = null, $id = null)
     {
        $arrayWorkingTime = array(7);

        if (is_null($bid)) {
            throw new NotFoundHttpException();
        }
        $Business = $this->businessRepository->find($bid);

        // 編集
        if ($id) {
            $BusinessStaff = $this->businessStaffRepository->find($id);
            if (is_null($BusinessStaff)) {
                throw new NotFoundHttpException();
            }

            $Staff = $this->staffRepository->find( $BusinessStaff->getStaff()->getId() );

            foreach( BusinessConfig::BUSINESS_WEEKLY as $key => $value){
                $result = $this->businessStaffWorkingTimeRepository
                    ->findOneBy([
                        'Business' => $BusinessStaff->getBusiness(),
                        'Staff' => $BusinessStaff->getStaff(),
                        'day_of_week' => $key,
                    ]);
                if( $result ){
                    $arrayWorkingTime[($key-1)] = array(
                        'day_of_week' => $result->getDayOfWeek()
                        ,'working_time_start' => $result->getWorkingTimeStart()
                        ,'working_time_end' => $result->getWorkingTimeEnd()
                    );
                }else{
                    $arrayWorkingTime[($key-1)] = array(
                        'day_of_week' => null
                        ,'working_time_start' => null
                        ,'working_time_end' => null
                    );
                }
            }
        // 新規登録
        } else {
            $status_id = ( isset($_POST["Status"]) ) ? $_POST["Status"] : 0;
            $staff_id = ( isset($_POST["Staff"]) ) ? $_POST["Staff"] : 0;

            $Status = $this->businessStatusRepository->find( $status_id );
            $Staff = $this->staffRepository->find( $staff_id );

            $BusinessStaff = new BusinessStaff();
            $BusinessStaff->setBusiness( $Business );
            foreach( BusinessConfig::BUSINESS_WEEKLY as $key => $value){
                $arrayWorkingTime[($key-1)] = array(
                    'day_of_week' => null
                    ,'working_time_start' => null
                    ,'working_time_end' => null
                );
            }
            $pagination = [];
        }
        // 案件登録フォーム
        $builder = $this->formFactory
            ->createBuilder(BusinessStaffType::class, $BusinessStaff);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {
            log_info('登録開始', [$BusinessStaff->getId()]);

            $this->entityManager->persist($BusinessStaff);
            $this->entityManager->flush();
            $this->entityManager->commit();
            $this->entityManager->refresh($BusinessStaff);

            // 曜日の削除
            foreach( $this->businessStaffWorkingTimeRepository->findBy(
                [
                    'Business' => $BusinessStaff->getBusiness(),
                    'Staff' => $BusinessStaff->getStaff(),
                ]) as $weekdata ) {
                $this->entityManager->remove($weekdata);
                $this->entityManager->flush();
                $this->entityManager->commit();
            }
            // 曜日の登録
            foreach( $_POST["admin_search_business"]["day_of_week"] as $weekno ){
/*
                $WorkingTime = $this->businessStaffWorkingTimeRepository->findOneBy(
                            [
                                'Business' => $BusinessStaff->getBusiness(),
                                'Staff' => $BusinessStaff->getStaff(),
                                'day_of_week' => $weekno
                            ]
                        );
                if( $WorkingTime ){
                }else{
                    $WorkingTime = new BusinessStaffWorkingTime();
                    $WorkingTime->setBusiness( $BusinessStaff->getBusiness() );
                    $WorkingTime->setStaff( $BusinessStaff->getStaff() );
                }
*/
                $WorkingTime = new BusinessStaffWorkingTime();
                $WorkingTime->setBusiness( $BusinessStaff->getBusiness() );
                $WorkingTime->setStaff( $BusinessStaff->getStaff() );
                $WorkingTime->setDayOfWeek( $weekno );
                $WorkingTime->setWorkingTimeStart( $_POST["admin_search_business"]["working_time_start"][$weekno-1] );
                $WorkingTime->setWorkingTimeEnd( $_POST["admin_search_business"]["working_time_end"][$weekno-1] );

                $this->entityManager->persist( $WorkingTime );
                $this->entityManager->flush();
            }
            $this->entityManager->commit();
//            $this->entityManager->refresh($WorkingTime);

            log_info('登録完了', [$BusinessStaff->getId()]);

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_business_staff_edit', [
                'bid' => $Business->getId(),
                'id' => $BusinessStaff->getId(),
            ]);
        }

        return [
            'form' => $form->createView(),
            'Business' => $Business,
            'BusinessStaff' => $BusinessStaff,
            'BusinessStaffWorkingTime' => $arrayWorkingTime,
            'BusinessConstractors' => BusinessConfig::BUSINESS_CONTRACTOR,
            'BusinessKinds' => BusinessConfig::BUSINESS_KIND,
            'BusinessWeekly' => BusinessConfig::BUSINESS_WEEKLY,
        ];
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
