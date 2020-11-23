<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Contact::class);
        $this->manager = $manager;
    }

    public function saveContact($name, $phone)
    {
        $newContact = new Contact();

        $newContact
            ->setName($name)
            ->setPhone($phone);

        $this->manager->persist($newContact);
        $this->manager->flush();
    }

    public function updateContact(Contact $contact): Contact
    {
        $this->manager->persist($contact);
        $this->manager->flush();

        return $contact;
    }


    public function removeContact(Contact $contact)
    {
        $this->manager->remove($contact);
        $this->manager->flush();
    }

    // /**
    //  * @return Contact[] Returns an array of Contact objects
    //  */
    public function findByField($value, $field)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.' . $field . ' = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByField($value, $field): ?Contact
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.' . $field . ' = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}