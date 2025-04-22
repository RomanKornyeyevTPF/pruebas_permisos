<?php

namespace App\Repository;

use App\Entity\Role;
use App\Entity\RolRecursoPermiso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RolRecursoPermiso>
 */
class RolRecursoPermisoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolRecursoPermiso::class);
    }

    //    /**
    //     * @return RolRecursoPermiso[] Returns an array of RolRecursoPermiso objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RolRecursoPermiso
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // public function usuarioTienePermiso(Role $rol, string $codigoAccion, string $nombreRecurso, string $codigoAmbito): ?string
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = "
    //         SELECT rrp.efecto
    //         FROM roles_recursos_permisos rrp
    //         JOIN acciones a ON rrp.accion_id = a.id
    //         JOIN recursos r ON rrp.recurso_id = r.id
    //         JOIN ambitos_datos ad ON rrp.ambito_id = ad.id
    //         WHERE rrp.rol_id = :rolId
    //           AND a.codigo = :accion
    //           AND r.nombre = :recurso
    //           AND ad.codigo = :ambito
    //         ORDER BY 
    //           CASE WHEN rrp.efecto = 'DENEGAR' THEN 1 ELSE 2 END
    //         LIMIT 1
    //     ";

    //     $result = $conn->fetchOne($sql, [
    //         'rolId'   => $rol->getId(),
    //         'accion'  => $codigoAccion,
    //         'recurso' => $nombreRecurso,
    //         'ambito'  => $codigoAmbito,
    //     ]);

    //     return $result ?: null; // 'PERMITIR', 'DENEGAR' o null
    // }


    public function buscarPermisosDeRol(Role $rol, string $accion, string $recurso, string $ambito): array
    {
        $qb = $this->createQueryBuilder('rrp')
            ->join('rrp.accion', 'a')
            ->join('rrp.recurso', 'r')
            ->join('rrp.ambito', 'ad')
            ->where('rrp.rol = :rolId')
            ->andWhere('a.codigo = :accion')
            ->andWhere('r.nombre = :recurso')
            ->andWhere('ad.codigo IN (:ambitos)')
            ->setParameter('rolId', $rol->getId())
            ->setParameter('accion', $accion)
            ->setParameter('recurso', $recurso)
            ->setParameter('ambitos', [$ambito]);
            

        return $qb->getQuery()->getResult();
    }

    
}
