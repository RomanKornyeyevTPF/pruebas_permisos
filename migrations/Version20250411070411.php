<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411070411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE acciones (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(50) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_29F5FFE720332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ambitos_datos (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(50) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5CC3AE2F20332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE grupos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_45842FE3A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuarios_grupos (grupo_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_2A9478239C833003 (grupo_id), INDEX IDX_2A947823DB38439E (usuario_id), PRIMARY KEY(grupo_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE grupos_roles (grupo_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_381D3E8A9C833003 (grupo_id), INDEX IDX_381D3E8AD60322AC (role_id), PRIMARY KEY(grupo_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permisos_directos_recursos (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, grupo_id INT DEFAULT NULL, recurso_id INT NOT NULL, accion_id INT NOT NULL, ambito_id INT NOT NULL, efecto VARCHAR(10) NOT NULL, INDEX IDX_3DA21A41DB38439E (usuario_id), INDEX IDX_3DA21A419C833003 (grupo_id), INDEX IDX_3DA21A41E52B6C4E (recurso_id), INDEX IDX_3DA21A413F4B5275 (accion_id), INDEX IDX_3DA21A41233A8A05 (ambito_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE recursos (id INT AUTO_INCREMENT NOT NULL, recurso_padre_id INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, tipo_recurso VARCHAR(50) NOT NULL, INDEX IDX_5163D17DB40B8AD0 (recurso_padre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, nombre_rol VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B63E2EC78DFB5740 (nombre_rol), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE roles_recursos_permisos (id INT AUTO_INCREMENT NOT NULL, rol_id INT NOT NULL, recurso_id INT NOT NULL, accion_id INT NOT NULL, ambito_id INT NOT NULL, efecto VARCHAR(10) NOT NULL, INDEX IDX_B3F947704BAB96C (rol_id), INDEX IDX_B3F94770E52B6C4E (recurso_id), INDEX IDX_B3F947703F4B5275 (accion_id), INDEX IDX_B3F94770233A8A05 (ambito_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, activo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_EF687F2E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuarios_roles (usuario_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_CE0972BADB38439E (usuario_id), INDEX IDX_CE0972BAD60322AC (role_id), PRIMARY KEY(usuario_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_grupos ADD CONSTRAINT FK_2A9478239C833003 FOREIGN KEY (grupo_id) REFERENCES grupos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_grupos ADD CONSTRAINT FK_2A947823DB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grupos_roles ADD CONSTRAINT FK_381D3E8A9C833003 FOREIGN KEY (grupo_id) REFERENCES grupos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grupos_roles ADD CONSTRAINT FK_381D3E8AD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos ADD CONSTRAINT FK_3DA21A41DB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos ADD CONSTRAINT FK_3DA21A419C833003 FOREIGN KEY (grupo_id) REFERENCES grupos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos ADD CONSTRAINT FK_3DA21A41E52B6C4E FOREIGN KEY (recurso_id) REFERENCES recursos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos ADD CONSTRAINT FK_3DA21A413F4B5275 FOREIGN KEY (accion_id) REFERENCES acciones (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos ADD CONSTRAINT FK_3DA21A41233A8A05 FOREIGN KEY (ambito_id) REFERENCES ambitos_datos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recursos ADD CONSTRAINT FK_5163D17DB40B8AD0 FOREIGN KEY (recurso_padre_id) REFERENCES recursos (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos ADD CONSTRAINT FK_B3F947704BAB96C FOREIGN KEY (rol_id) REFERENCES roles (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos ADD CONSTRAINT FK_B3F94770E52B6C4E FOREIGN KEY (recurso_id) REFERENCES recursos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos ADD CONSTRAINT FK_B3F947703F4B5275 FOREIGN KEY (accion_id) REFERENCES acciones (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos ADD CONSTRAINT FK_B3F94770233A8A05 FOREIGN KEY (ambito_id) REFERENCES ambitos_datos (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_roles ADD CONSTRAINT FK_CE0972BADB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_roles ADD CONSTRAINT FK_CE0972BAD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_grupos DROP FOREIGN KEY FK_2A9478239C833003
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_grupos DROP FOREIGN KEY FK_2A947823DB38439E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grupos_roles DROP FOREIGN KEY FK_381D3E8A9C833003
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grupos_roles DROP FOREIGN KEY FK_381D3E8AD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos DROP FOREIGN KEY FK_3DA21A41DB38439E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos DROP FOREIGN KEY FK_3DA21A419C833003
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos DROP FOREIGN KEY FK_3DA21A41E52B6C4E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos DROP FOREIGN KEY FK_3DA21A413F4B5275
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permisos_directos_recursos DROP FOREIGN KEY FK_3DA21A41233A8A05
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recursos DROP FOREIGN KEY FK_5163D17DB40B8AD0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos DROP FOREIGN KEY FK_B3F947704BAB96C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos DROP FOREIGN KEY FK_B3F94770E52B6C4E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos DROP FOREIGN KEY FK_B3F947703F4B5275
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles_recursos_permisos DROP FOREIGN KEY FK_B3F94770233A8A05
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_roles DROP FOREIGN KEY FK_CE0972BADB38439E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuarios_roles DROP FOREIGN KEY FK_CE0972BAD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE acciones
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ambitos_datos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE grupos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuarios_grupos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE grupos_roles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permisos_directos_recursos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE recursos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE roles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE roles_recursos_permisos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuarios
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuarios_roles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
