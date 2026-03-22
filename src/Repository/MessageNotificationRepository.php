<?php

namespace App\Repository;

use App\Entity\MessageNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageNotification>
 */
class MessageNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageNotification::class);
    }

    /** @return MessageNotification[] */
    public function findUnreadForUser(int $userId): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.notifiedUser = :userId')
            ->andWhere('n.isRead = false')
            ->setParameter('userId', $userId)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countUnreadForUser(int $userId): int
    {
        return (int) $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->andWhere('n.notifiedUser = :userId')
            ->andWhere('n.isRead = false')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findForMessage(int $messageId): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.message = :messageId')
            ->setParameter('messageId', $messageId)
            ->getQuery()
            ->getResult();
    }

    public function markAllAsReadForUser(int $userId): int
    {
        $unread = $this->findUnreadForUser($userId);
        if (empty($unread)) {
            return 0;
        }

        foreach ($unread as $notification) {
            $notification->markAsRead();
        }

        $this->getEntityManager()->flush();

        return count($unread);
    }
}
