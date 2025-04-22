# PERMISOS
## VOTERS
<p>
  A la hora de hacer voters, hay varias formas de comprobar el resultado. A la hora de ejecutar una acción (ej: cerrar un aval) pondremos la siguiente condición:

  ```php
  $this->denyAccessUnlessGranted('CERRAR_AVAL', $aval);
  ```

  Esto está utilizando el sistema de seguridad de Symfony para verificar si el usuario autenticado tiene permisos para realizar la acción 'ABRIR_AVAL' sobre el objeto $aval.

  Esto internamente hace un recorrido por todos los votantes, para ver qué votante soporta estas condiciones ('CERRAR_AVAL', $aval). Estp se comprueba en cada votante con el método `supports` que retorna true/false.

  Una vez encontrado el voter compatible, se ejecuta su método `voteOnAttribute` que retorna true/false en base a las condiciones.
  - Si algún voter devuelve true: acceso permitido
  - Si ningún voter devuelve true: acceso denegado
  - Si no hay un voter compatible: acceso denegado

  > **Nota:** Symfony solo necesita una respuesta positiva para permitir el acceso.

  Hay varias maneras de hacer las comprobaciones en un voter. O bien se pueden pasar como parámetros extra o bien esos datos se extraen de la BD del propio objeto (recomendado).

  ```php
  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    $usuario = $token->getUser();
    if (!$usuario instanceof Usuario) {
      return false;
    }

    $aval = $subject;
    $accion = self::SUPPORTED_ACTIONS[$attribute];
    $recurso = 'Cerrar Aval'; // nombre exacto como en la tabla recursos
    $ambito = 'TODOS'; // más adelante puede venir de info del Aval (por país, etc)

    // Revisión por roles del usuario
    foreach ($usuario->getRolesBbdd() as $rol) {
      $efecto = $this->rolPermisoRepo->usuarioTienePermiso($rol, $accion, $recurso, $ambito);
      if ($efecto === 'DENEGAR') return false;
      if ($efecto === 'PERMITIR') return true;
    }
    die;

    // Revisión por permisos directos
    $efecto = $this->permisoDirectoRepo->usuarioTienePermisoDirecto($usuario, $accion, $recurso, $ambito);
    if ($efecto === 'DENEGAR') return false;
    if ($efecto === 'PERMITIR') return true;

    return false;
  }
  ```

  - **Opción 1 (recomendada):** inferir desde $aval. Si en este caso $recurso y $ambito dependen del contenido del objeto $aval, entonces lo más limpio es obtenerlos dentro del Voter.
    ```php
    $recurso = $aval->getTipoRecurso(); // por ejemplo
    $ambito = $aval->getPais() ?: 'TODOS';
    ```
    Por lo cual, el método se seguiría ejecutando de esta manera:
    ```php
    $this->denyAccessUnlessGranted('ABRIR_AVAL', $aval);
    ```

  - **Opción 2:** pasar múltiples parámetros (si no se puede inferir / editar BD). En este caso se pasaría como 2do param un array con los datos extra:
    ```php
    $this->denyAccessUnlessGranted('ABRIR_AVAL', [
      'aval' => $aval,
      'recurso' => 'Cerrar Aval',
      'ambito' => 'TODOS',
    ]);
    ```
    Entonces en el voter:
    ```php
    protected function supports(string $attribute, $subject): bool
    {
      return is_array($subject)
        && isset($subject['aval'])
        && $subject['aval'] instanceof Aval
        && array_key_exists($attribute, self::SUPPORTED_ACTIONS);
    }
    ```

    Por lo que las variables en voteOnAttribute se recogerían así:
    ```php
    $aval = $subject['aval'];
    $recurso = $subject['recurso'];
    $ambito = $subject['ambito'];
    ```
</p>

<br><br><br><hr><br><br><br>

## EJEMPLO DE AVALES (Sistema de permisos con Voter y ámbito nacional/internacional)

## 🎯 Objetivo
Controlar el acceso a recursos (como avales) mediante permisos definidos por rol, acción, recurso y ámbito de datos (`NACIONAL` o `INTERNACIONAL`).

---

## 🧱 Estructura de la base de datos

Tabla `roles_recursos_permisos`:

| id | rol_id | recurso_id | accion_id | ambito_id | efecto   | explicación               |
|----|--------|------------|-----------|-----------|----------|---------------------------|
| 5  | 4      | 4          | 7         | 4         | PERMITIR | ABRIR AVAL INTERNACIONAL  |
| 6  | 4      | 4          | 7         | 2         | DENEGAR  | ABRIR AVAL NACIONAL       |
| 7  | 4      | 3          | 5         | 4         | PERMITIR | CERRAR AVAL INTERNACIONAL |
| 8  | 4      | 3          | 5         | 2         | DENEGAR  | CERRAR AVAL NACIONAL      |

Tabla `ambitos_datos` contiene los códigos `'NACIONAL'`, `'INTERNACIONAL'`, `'TODOS'`, etc.

---

## 🧠 Lógica del Voter

### ¿Cómo se determina el ámbito?
Se añade un campo `pais` (`varchar(2)`) en la entidad `Aval`, que se interpreta así:

```php
public function getAmbito(): string
{
  return $this->pais === 'ES' ? 'NACIONAL' : 'INTERNACIONAL';
}
```

> **Nota:** Esto se puede hacer más flexible con un array de países nacionales si es necesario.

---

## 🔎 Query en el `RolPermisoRepository`

```php
public function buscarPermisosDeRol(Role $rol, string $accion, string $recurso, string $ambito): array
{
  return $this->createQueryBuilder('rrp')
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
    ->setParameter('ambitos', [$ambito, 'TODOS'])
    ->getQuery()
    ->getResult();
}
```

> Permite colisiones entre `NACIONAL`, `INTERNACIONAL` y `'TODOS'`.

---

## 🛡️ Gestión de colisiones

El sistema evalúa los permisos en orden de prioridad:

1. Si hay algún permiso con efecto `DENEGAR` → acceso denegado.
2. Si hay uno o más permisos con efecto `PERMITIR` → acceso concedido.
3. Si no hay permisos → acceso denegado por defecto.

---

## 🧪 Ejemplo

### Caso 1: Aval en EEUU (INTERNACIONAL)
El rol `ROLE_W_AVAL_INTERNACIONAL` tiene:

```sql
INSERT INTO roles_recursos_permisos 
(rol_id, recurso_id, accion_id, ambito_id, efecto) 
VALUES (4, 4, 4, 4, 'PERMITIR');
```

Si el aval tiene `pais = 'US'`, se interpreta como `INTERNACIONAL` → acceso PERMITIDO.

### Caso 2: Aval en España (NACIONAL)
Sin permiso explícito → acceso DENEGADO.

---

## 📌 Conclusión

Este sistema es:

- Escalable: puedes añadir nuevos ámbitos y países fácilmente.
- Flexible: permite combinaciones como "permitir internacionales pero no nacionales".
- Mantenible: la lógica está centralizada en el voter y en los métodos de la entidad.