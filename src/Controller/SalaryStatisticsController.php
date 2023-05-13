<?php

namespace App\Controller;

use App\Entity\SalaryStatistics;
use App\Entity\Staff;
use App\Form\SalaryStatisticsType;
use App\Repository\SalaryStatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/salary-statistics")
 */
class SalaryStatisticsController extends AbstractController
{
    private SalaryStatisticsRepository $repo;
    public function __construct(SalaryStatisticsRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * @Route("/salary-table", name="salaryTable")
     */
    public function salaryTableAction(): Response
    {
        $salary = $this->repo->findAll();
        return $this->render('salary_statistics/salaryTable.html.twig', [
            'salary'=>$salary
        ]);
    }

    /**
     * @Route("/", name="salaryStatisticsPage")
     */
    public function salaryStatisticsPageAction(): Response
    {
        $staff = $this->repo->SalaryStatistics();//complex data
        $data = [];//empty array
        foreach($staff as $s){
            if($s['Number_Of_Working_Days']>28){
                $s['Total_Salary'] = $s['basicSalary']*$s['coefficientsSalary']+500000;
            }
            else{
                $s['Total_Salary'] = $s['basicSalary']*$s['coefficientsSalary'];
            }
            // $s= array($s);
            $data[] = $s;
        }
        return $this->render('salary_statistics/table.html.twig', [
            'salary'=>$data
        ]);
        // return $this->json($data);
    }

    /**
    * @Route("/add", name="salaryStatisticsAdd",requirements={"id"="\d+"})
    */
    public function addAction(Request $req, SluggerInterface $slugger): Response
    {
        $sal = new SalaryStatistics();
        $form = $this->createForm(SalaryStatisticsType::class, $sal);   
 
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
         $this->repo->add($sal,true);
         return $this->redirectToRoute('salaryTable', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('salary_statistics/form.html.twig',[
         'form' => $form->createView()
        ]);
     }

    /**
    * @Route("/edit/{id}", name="salaryStatisticsEdit",requirements={"id"="\d+"})
    */
    public function editAction(Request $req, SalaryStatistics $sal,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SalaryStatisticsType::class, $sal);   
 
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
         $this->repo->add($sal,true);
         return $this->redirectToRoute('salaryTable', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('salary_statistics/form.html.twig',[
         'form' => $form->createView()
        ]);
     }

    /**
     * @Route("/delete/{id}",name="salaryStatisticsDelete",requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, SalaryStatistics $sal): Response
    {
        $this->repo->remove($sal,true);
        return $this->redirectToRoute('salaryTable', [], Response::HTTP_SEE_OTHER);
    }

    // /**
    //  * @Route("/salary-table", name="salaryStatisticstable")
    //  */
    // public function salaryStatisticstableAction(): Response
    // {
    //     $salary = $this->repo->findAll();
    //     return $this->render('salary_statistics/salaryTable.html.twig', [
    //         'salary'=>$salary
    //     ]);
    // }

    // /**
    //  * @Route("/table", name="salaryStatistics")
    //  */
    // public function salaryStatisticsAction(): Response
    // {
    //     $salary = $this->repo->SalaryStatistics();
    //     return $this->render('salary_statistics/index.html.twig', [
    //         'salary'=>$salary
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="findStaffSalaryById")
    //  */
    // public function findStaffSalaryById($id): Response
    // {
    //     $salary = $this->repo->SalaryStatistics($id);
    //     return $this->render('salary_statistics/table.html.twig',[
    //         'salary'=>$salary
    //     ]);
    // }

    // /**
    //  * @Route("/salary-table", name="findStaffSalary")
    //  */
    // public function findStaffSalary(): Response
    // {
    //     $salary = $this->repo->SalaryStatistics();
    //     // return $this->json($salary);
    //     return $this->render('salary_statistics/table.html.twig', [
    //         'salary'=>$salary
    //     ]);
    // }

    // /**
    //  * @Route("/table", name="table")
    //  */
    // public function tableAction(Staff $st, SalaryStatistics $ss): Response
    // {
    //     $table = [
    //         'id'=>$st->getId(),
    //         'name'=>$st->getName(),
    //         'basicSalary'=>$ss->getBasicSalary(),
    //         'coefficientsSalary'=>$ss->getCoefficientsSalary()
    //     ];

    //     // return $this->render('salary_statistics/table.html.twig', [
    //     //     'table'=>$table
    //     // ]);
    //     return $this->json($table);
    // }
}
