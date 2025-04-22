<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    //    /**
    //     * @return Usuario[] Returns an array of Usuario objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Usuario
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Método personalizado para encontrar un usuario por email
    public function findOneByEmail($email): ?Usuario
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getPermisosPorRol(Usuario $usuario): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT r.nombre_rol, rec.nombre AS recurso, a.codigo AS accion, ad.codigo AS ambito, rrp.efecto
            FROM usuarios_roles ur
            JOIN roles r ON ur.role_id = r.id
            JOIN roles_recursos_permisos rrp ON rrp.rol_id = r.id
            JOIN recursos rec ON rrp.recurso_id = rec.id
            JOIN acciones a ON rrp.accion_id = a.id
            JOIN ambitos_datos ad ON rrp.ambito_id = ad.id
            WHERE ur.usuario_id = :usuarioId
        ";

        return $conn->executeQuery($sql, ['usuarioId' => $usuario->getId()])->fetchAllAssociative();
    }

    public function getPermisosDirectos(Usuario $usuario): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT rec.nombre AS recurso, a.codigo AS accion, ad.codigo AS ambito, pdr.efecto
            FROM permisos_directos_recursos pdr
            JOIN recursos rec ON pdr.recurso_id = rec.id
            JOIN acciones a ON pdr.accion_id = a.id
            JOIN ambitos_datos ad ON pdr.ambito_id = ad.id
            WHERE pdr.usuario_id = :usuarioId
        ";

        return $conn->executeQuery($sql, ['usuarioId' => $usuario->getId()])->fetchAllAssociative();
    }

}
