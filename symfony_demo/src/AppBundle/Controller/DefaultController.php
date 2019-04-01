<?php

namespace AppBundle\Controller;

use App\SimpleFileSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) : Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request) : Response
    {
        $contains = $request->request->get('contains');
        if (empty($contains)) {
            return $this->render('default/index.html.twig', [
                'error' => 'Please provide some content to search for.',
            ]);
        }
        $contains = \explode(',', \htmlspecialchars($contains));
        \array_walk($contains, function (&$content) {
            $content = \trim($content);
            if (\preg_match('{^#.+#[imsxADSUXJu]*$}', $content) !== 1) {
                $content = \sprintf('#%s#', $content);
            }
        });

        $simpleFileSearch = new SimpleFileSearch(__DIR__.'/../../../../public/demo_files');

        $result = $simpleFileSearch->contains($contains)->find();
        $result = \iterator_to_array($result);
        if (empty($result)) {
            return $this->render('default/index.html.twig', [
                'error' => 'No files found!',
            ]);
        }

        return $this->render('default/index.html.twig', ['files' => $result]);
    }
}
