<?php

namespace App\Bridge\Symfony\Controller;

use App\Application\Service\CreateQuestionary;
use App\Application\Service\CreateQuestionaryService;
use App\Application\Service\FillUpQuestionaryClientAnswers;
use App\Application\Service\FillUpQuestionaryClientAnswersService;
use App\Application\Service\GetQuestionary;
use App\Application\Service\GetQuestionaryQuestions;
use App\Application\Service\GetQuestionaryQuestionsService;
use App\Application\Service\GetQuestionaryService;
use DomainException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuestionaryController extends AbstractController
{
    public function __construct(
        private readonly GetQuestionaryQuestionsService        $getQuestionaryQuestionsService,
        private readonly CreateQuestionaryService              $createQuestionaryService,
        private readonly FillUpQuestionaryClientAnswersService $fillUpQuestionaryClientAnswersService,
        private readonly GetQuestionaryService                 $getQuestionaryService
    )
    {

    }

    #[Route('/', name: 'app_questionary')]
    public function index(): Response
    {
        return $this->render('questionary/index.html.twig');
    }

    #[Route('/create-questionary', name: 'create_questionary')]
    public function create(Request $request): Response
    {
        try {
            $questionaryUuid = $request->query->get('questionaryUuid');

            if (empty($questionaryUuid)) {
                $fullName = $request->request->get('fullName', '');
                $questionaryUuid = $this->createQuestionaryService->execute(new CreateQuestionary($fullName));
            }

            $questions = $this->getQuestionaryQuestionsService->execute(new GetQuestionaryQuestions($questionaryUuid));
        } catch (RuntimeException|DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_questionary');
        }

        return $this->render('questionary/questionary.html.twig', [
            'questions' => $questions,
            'questionaryUuid' => $questionaryUuid,
        ]);
    }

    #[Route('/fill-up', name: 'fill_up_questionary')]
    public function fillUpQuestionary(Request $request): Response
    {
        $request = $request->request->all();
        $questionaryUuid = $request['questionaryUuid'];
        unset($request['questionaryUuid']);

        try {
            $this->fillUpQuestionaryClientAnswersService->execute(new FillUpQuestionaryClientAnswers($questionaryUuid, $request));
            $questionary = $this->getQuestionaryService->execute(new GetQuestionary($questionaryUuid));

        } catch (RuntimeException|DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('create_questionary', ['questionaryUuid' => $questionaryUuid]);
        }

        return $this->render('questionary/questionary_result.html.twig', [
            'questionary' => $questionary
        ]);
    }
}
