<?php

namespace App\Repository;

use App\Entity\PermisoDirectoRecurso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PermisoDirectoRecurso>
 */
class PermisoDirectoRecursoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PermisoDirectoRecurso::class);
    }

    //    /**
    //     * @return PermisoDirectoRecurso[] Returns an array of PermisoDirectoRecurso objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PermisoDirectoRecurso
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function usuarioTienePermisoDirecto(
        \App\Entity\Usuario $usuario,
        string $accionCodigo,
        string $recursoNombre,
        string $ambitoCodigo
    ): ?string {
        $conn = $this->getEntityManager()->getConnection();
    
        $sql = "
            SELECT pdr.efecto
            FROM permisos_directos_recursos pdr
            INNER JOIN acciones a ON pdr.accion_id = a.id
            INNER JOIN recursos r ON pdr.recurso_id = r.id
            INNER JOIN ambitos_datos ad ON pdr.ambito_id = ad.id
            WHERE pdr.usuario_id = :usuarioId
              AND a.codigo = :accion
              AND r.nombre = :recurso
              AND ad.codigo = :ambito
            LIMIT 1
        ";
    
        $result = $conn->executeQuery($sql, [
            'usuarioId' => $usuario->getId(),
            'accion' => $accionCodigo,
            'recurso' => $recursoNombre,
            'ambito' => $ambitoCodigo,
        ])->fetchOne();
    
        return $result ?: null;
    }
    
}
