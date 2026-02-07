<?php
namespace Customize\Controller;

use Eccube\Controller\AbstractController;
use Customize\Repository\ReportRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * ReportController constructor.
     */
    public function __construct()
    {
    }

    /**
     * TOPページ
     *
     * @Route("/report", name="report", methods={"GET"})
     * @Template("Report/index.twig")
     */
    public function index()
    {
        return [];
    }

    /**
     * ログイン.
     *
     * @Route("/report/login", name="report_login", methods={"GET"})
     * @Template("Report/login.twig")
     */
    public function login()
    {
        return [];
    }

    /**
     * 業務報告.
     *
     * @Route("/report/report", name="report_report", methods={"GET"})
     * @Template("Report/report.twig")
     */
    public function report()
    {
        return [];
    }

}
