INSERT INTO acciones (id, codigo, descripcion) VALUES
  (1, 'LEER', 'Visualizar'),
  (2, 'AÑADIR', 'Añadir nuevos registros'),
  (3, 'BORRAR', 'Eliminar registros'),
  (4, 'EDITAR', 'Modificar registros'),
  (5, 'CERRAR', 'Cerrar proceso o flujo'),
  (6, 'ABRIR', 'Abrir proceso o flujo'),
  (7, 'EXPORTAR', 'Exportar datos');

INSERT INTO ambitos_datos (id, codigo, descripcion) VALUES
  (1, 'TODOS', 'Todos los registros'),
  (2, 'PAIS', 'Filtrado por país'),
  (3, 'DEPARTAMENTO', 'Filtrado por departamento');


INSERT INTO recursos (id, nombre, tipo_recurso, recurso_padre_id) VALUES
  (1, 'Contratos', 'MODULO', NULL),
  (2, 'Avales', 'MODULO', NULL),
  (3, 'Cerrar Aval', 'FUNCIONALIDAD', 2), -- funcionalidad hija del módulo Avales
  (4, 'Abrir Aval', 'FUNCIONALIDAD', 2);

INSERT INTO roles (id, nombre_rol, descripcion) VALUES
  (1, 'ROLE_CONTRATOS', 'Acceso al módulo contratos'),
  (2, 'ROLE_AVALES', 'Acceso al módulo avales'),
  (3, 'ROLE_AVALES_ADMIN', 'Administra funcionalidades avanzadas de avales'),
  (4, 'ROLE_W_AVAL_INTERNACIONAL', 'WRITE AVALES INTERNACIONALES');


-- TODAS LAS CONTRASEÑAS SON 123456
INSERT INTO usuarios (id, email, password, nombre, activo) VALUES
  (1, 'juan@empresa.com', '$2y$10$zPkW5FX2q..s1SRG7aZe3ukNYe4bfEvAWeZY7G6rvqFtvjE59.P86', 'Juan Pérez', true),
  (2, 'ana@empresa.com', '$2y$10$zPkW5FX2q..s1SRG7aZe3ukNYe4bfEvAWeZY7G6rvqFtvjE59.P86', 'Ana Gómez', true),
  (3, 'luis@empresa.com', '$2y$10$zPkW5FX2q..s1SRG7aZe3ukNYe4bfEvAWeZY7G6rvqFtvjE59.P86', 'Luis Ramírez', true);

INSERT INTO usuarios_roles (usuario_id, role_id) VALUES
  (1, 1), -- Juan tiene ROLE_CONTRATOS
  (2, 2), -- Ana tiene ROLE_AVALES
  (3, 2), -- Luis tiene ROLE_AVALES y...
  (3, 3); -- ...también ROLE_AVALES_ADMIN

  
-- ROLE_CONTRATOS puede LEER contratos
INSERT INTO roles_recursos_permisos (rol_id, recurso_id, accion_id, ambito_id, efecto)
VALUES (1, 1, 1, 1, 'PERMITIR');

-- ROLE_AVALES puede LEER avales
INSERT INTO roles_recursos_permisos (rol_id, recurso_id, accion_id, ambito_id, efecto)
VALUES (2, 2, 1, 1, 'PERMITIR');

-- ROLE_AVALES_ADMIN puede CERRAR avales (funcionalidad específica)
INSERT INTO roles_recursos_permisos (rol_id, recurso_id, accion_id, ambito_id, efecto)
values
	(1, 1, 1, 1, 'PERMITIR'),
	(2, 2, 1, 1, 'PERMITIR'),
	(3, 3, 5, 1, 'PERMITIR'),
	(4, 4, 7, 4, 'PERMITIR'), -- abrir aval internacional
	(4, 4, 7, 2, 'DENEGAR'),  -- abrir aval nacional
	(4, 3, 5, 4, 'PERMITIR'), -- cerrar aval internacional
	(4, 3, 5, 2, 'DENEGAR');  -- abrir aval nacional

-- A Ana le damos un permiso directo para CERRAR avales aunque no tenga el rol de admin
INSERT INTO permisos_directos_recursos (usuario_id, recurso_id, accion_id, ambito_id, efecto)
VALUES (2, 3, 5, 1, 'PERMITIR');


INSERT INTO avales (id, estado)
VALUES 
    (1, 'abierto', 'EEUU'),
    (2, 'cancelado', 'España'),
    (3, 'cerrado', 'EEUU');