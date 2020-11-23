<?php
namespace App\Controller;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class ContactController
{
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("contact", name="add_contact", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $phone = $data['phone'];

        if (empty($name) || empty($phone)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->contactRepository->saveContact($name, $phone);

        return new JsonResponse(['status' => 'Contact created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("contact/{id}", name="get_one_contact", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'phone' => $contact->getPhone()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("contacts", name="get_all_contacts", methods={"GET"})
     */
    public function getAll(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $phone = $request->query->get('phone');
        
        if (!empty($name)) {
            $contacts = $this->contactRepository->findByField($name, 'name');
        } elseif (!empty($phone)) {
            $contacts = $this->contactRepository->findByField($phone, 'phone');
        } else {
            $contacts = $this->contactRepository->findAll();
        }
        $data = [];

        foreach ($contacts as $contact) {
            $data[] = [
                'id' => $contact->getId(),
                'name' => $contact->getName(),
                'phone' => $contact->getPhone()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("contact/{id}", name="update_contact", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $contact->setName($data['name']);
        empty($data['phone']) ? true : $contact->setType($data['phone']);

        $updatedContact = $this->contactRepository->updateContact($contact);

		return new JsonResponse(['status' => 'Contact updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("contact/{id}", name="delete_contact", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        $this->contactRepository->removeContact($contact);

        return new JsonResponse(['status' => 'Contact deleted'], Response::HTTP_OK);
    }
}

?>
