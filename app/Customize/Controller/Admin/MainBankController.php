<?php

namespace Customize\Controller\Admin;

use Customize\Entity\MainBank;
use Customize\Form\Type\Admin\MainBankType;
use Customize\Repository\MainBankRepository;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
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

class MainBankController extends AbstractController
{
    /**
     * @var MainBankRepository
     */
    protected $mainBusinessRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * OrderController constructor.
     *
     * @param MainBankRepository $mainBusinessRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        MainBankRepository $mainBusinessRepository,
        ValidatorInterface $validator
    ) {
        $this->mainBusinessRepository = $mainBusinessRepository;
        $this->validator = $validator;
    }

    /**
     * 一覧画面.
     * @Route("/%eccube_admin_route%/main_bank", name="admin_main_bank", methods={"GET", "POST"})
     * @Template("@admin/MainBank/index.twig")
     */
    public function index(Request $request, $id = 1)
    {
        // 編集
        if ($id) {
            $MainBank = $this->mainBusinessRepository->find($id);
        } else {
            $MainBank = new MainBank();
        }
        // 登録フォーム
        $builder = $this->formFactory
            ->createBuilder(MainBankType::class, $MainBank);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            log_info('登録開始', [$MainBank->getId()]);

            $this->entityManager->persist($MainBank);
            $this->entityManager->flush();

            log_info('登録完了', [$MainBank->getId()]);

            $this->addSuccess('admin.common.save_complete', 'admin');
        }

        return [
            'form' => $form->createView(),
            'MainBank' => $MainBank,
        ];
    }

}
