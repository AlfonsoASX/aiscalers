# AiScalers.co - PRD MVP (Club de Negocios Automatizado)

## Objetivo del producto
Plataforma de membresía privada donde dueños de negocios acceden desde web y escritorio (PWA) a herramientas preconfiguradas para automatizar sus operaciones, con énfasis en flujos de copiar/pegar y consumo rápido de recursos.

## Alcance del MVP
- Área pública (landing) y área privada (dashboard) con acceso solo para miembros activos.
- Módulos incluidos: Identidad y Acceso, Bóveda de Sistemas, Consultor IA, Prompt-Teca Ejecutiva, Biblioteca Inteligente (Libros + Podcasts), Gestión de Usuario.
- Onboarding guiado en los primeros tres pasos tras el primer inicio de sesión.
- Experiencia estilo app nativa: interfaz oscura, minimalista, prioriza velocidad y acciones de copia.

## Supuestos y restricciones
- La plataforma es de membresía; todo el contenido core se muestra solo a usuarios autenticados con suscripción activa.
- El onboarding debe ser ignorado en sesiones siguientes una vez completado.
- El chat del Consultor IA debe mantener contexto durante la sesión, limitado al navegador/ventana activa.
- Toda acción de copia (blueprints, textos generados) debe dar feedback visual inmediato.

## Entidades de datos (conceptual)
- **Usuario**: nombre, email, foto/avatares, rol (miembro/administrador), estado de membresía (activa, expirada, cancelada), fecha de renovación, método de pago referenciado, progreso de onboarding (pasos completados, fecha de último paso), preferencias de notificación, historial de inicio de sesión.
- **Catálogo de Sistemas (Bóveda)**: identificador, título, categoría, descripción corta, video explicativo embebido (URL/ID), texto de configuración (blueprint), instrucciones de implementación, etiquetas, nivel de complejidad, fecha de última actualización, métricas de uso (copias realizadas).
- **Catálogo Biblioteca (Libros + Podcasts)**: identificador, título, autor, categoría/tema, portada (imagen), archivo documento (URL interna), archivo audio de resumen ejecutivo (URL interna), duración del audio, descripción corta, nivel recomendado, indicadores de descarga/escucha, fecha de publicación.
- **Prompt-Teca**: identificador, título, categoría, texto base con marcadores/variables, campos requeridos, versión, fecha de actualización, contador de usos.
- **Sesión de chat**: identificador de sesión, usuario asociado, contexto preconfigurado, mensajes (rol, contenido, timestamp), estado (activa/archivada), duración.
- **Suscripción**: plan, precio, moneda, ciclo, estado de pago, historial de transacciones, método de pago tokenizado, fecha de próxima factura.

## Historias de Usuario y Criterios de Aceptación

### 1) Módulo de Identidad y Acceso
**Historia 1.1**: Como visitante, quiero ver una landing pública para entender la propuesta y solicitar acceso.
- Criterios de aceptación:
  - La landing es accesible sin iniciar sesión y muestra CTA a registro/compra.
  - Los enlaces a Términos/Privacidad son visibles.

**Historia 1.2**: Como miembro registrado, quiero iniciar sesión y acceder al dashboard solo si mi membresía está activa.
- Criterios de aceptación:
  - El sistema valida credenciales y estado de membresía antes de mostrar el dashboard.
  - Usuarios con membresía inactiva ven mensaje de estado y opción para actualizar pago.
  - Sesiones expiran tras periodo de inactividad configurable.

**Historia 1.3**: Como nuevo miembro, quiero ser guiado por los primeros tres pasos al entrar por primera vez.
- Criterios de aceptación:
  - Al primer inicio de sesión se muestra un onboarding con 3 pasos claros (ej. configurar perfil, ver primer blueprint, usar consultor).
  - El progreso se guarda por paso; si el usuario sale, retoma en el siguiente inicio.
  - Una vez completado, el onboarding no se vuelve a mostrar, pero es reconsultable desde el perfil.

### 2) Módulo "Bóveda de Sistemas" (Core)
**Historia 2.1**: Como miembro, quiero ver un catálogo visual de automatizaciones con título, categoría y video embebido.
- Criterios de aceptación:
  - El catálogo muestra tarjetas con título, categoría y un reproductor embebido disponible sin salir de la página.
  - Se puede filtrar o buscar por categoría/etiquetas.
  - Al seleccionar un ítem se despliega detalle con instrucciones.

**Historia 2.2 (Crítica)**: Como miembro, quiero copiar el "Blueprint" al portapapeles y saber que se copió.
- Criterios de aceptación:
  - Cada ítem incluye botón destacado "Copiar Blueprint".
  - Al hacer clic se copia la cadena completa de configuración al portapapeles sin modificarla.
  - Se muestra confirmación visual (toast/alerta) de que el texto está listo para pegar.
  - El botón indica claramente si la copia falló e invita a reintentar.

### 3) Módulo "Consultor IA"
**Historia 3.1**: Como miembro, quiero chatear con un consultor con contexto pre-cargado sobre la metodología del club.
- Criterios de aceptación:
  - El primer mensaje del sistema incluye o aplica el contexto predefinido de la metodología.
  - El usuario puede enviar y recibir mensajes de manera continua.
  - El historial se mantiene durante la sesión activa, permitiendo scroll y recuperación de mensajes previos sin recargar.

**Historia 3.2**: Como miembro, quiero volver a una conversación previa durante la misma sesión del navegador.
- Criterios de aceptación:
  - Al recargar la página dentro de la misma sesión, se restaura el historial local.
  - Existe opción para limpiar contexto y empezar una nueva conversación.

### 4) Módulo "Prompt-Teca Ejecutiva"
**Historia 4.1**: Como miembro, quiero seleccionar una plantilla y rellenar campos para generar un texto final listo para copiar.
- Criterios de aceptación:
  - Cada plantilla muestra campos obligatorios/optativos (ej. Nombre del Producto, Audiencia) antes de generar.
  - Tras completar campos, el sistema compone el texto final sustituyendo variables.
  - El resultado se muestra en pantalla con opción de copiar al portapapeles y confirmación visual.
  - Los campos se pueden editar y regenerar sin perder el historial de entradas previas en la sesión.

**Historia 4.2**: Como miembro, quiero guardar o marcar plantillas favoritas para rápido acceso.
- Criterios de aceptación:
  - Se puede marcar/desmarcar una plantilla como favorita.
  - Existe vista/filtrado por favoritas dentro del módulo.

### 5) Módulo "Biblioteca Inteligente" (Libros + Podcasts)
**Historia 5.1**: Como miembro, quiero ver un catálogo de libros/podcasts con portada y descripción breve.
- Criterios de aceptación:
  - Cada elemento muestra portada, título, autor y breve descripción.
  - Se puede filtrar por categoría/tema.

**Historia 5.2 (Crítica)**: Como miembro, quiero reproducir un resumen ejecutivo en audio sin salir de la página antes de descargar el documento.
- Criterios de aceptación:
  - Cada libro incluye un reproductor de audio embebido visible en su ficha.
  - Al reproducir, el audio inicia sin redirigir o abrir nuevas páginas.
  - Controles básicos (play/pausa/seek/volumen) están disponibles.
  - El usuario puede descargar el documento completo desde la misma ficha después o mientras escucha.

### 6) Módulo de Gestión de Usuario
**Historia 6.1**: Como miembro, quiero ver mi estado de suscripción y fecha de renovación.
- Criterios de aceptación:
  - El perfil muestra estado (activa, expirada, cancelada), plan y próxima fecha de facturación.
  - Mensajes claros indican acciones recomendadas si hay problemas de pago.

**Historia 6.2**: Como miembro, quiero actualizar mi método de pago.
- Criterios de aceptación:
  - Desde el dashboard se accede a un portal seguro donde se pueden actualizar los datos de pago.
  - Tras actualizar, se refleja el nuevo método y se confirma al usuario.

**Historia 6.3**: Como miembro, quiero cancelar mi suscripción de forma autogestionada.
- Criterios de aceptación:
  - Existe opción visible para cancelar dentro del portal de pagos o perfil.
  - Se solicita confirmación antes de cancelar y se muestra la fecha efectiva de terminación.
  - Tras cancelar, el estado cambia a "cancelada" y el acceso al dashboard se revoca al vencer el periodo pagado.

## Requerimientos de Experiencia de Usuario (UX)
- Interfaz oscura, minimalista y orientada a ejecutivos; tipografía clara y jerarquías visuales sencillas.
- Interacciones rápidas: transiciones cortas, precarga de catálogos y feedback inmediato en copias/acciones.
- Diseño PWA: navegación fluida tipo app, atajos a funciones frecuentes (Copiar Blueprint, Abrir Chat, Reproducir Audio).
- Preferencia por layouts de tarjetas y paneles laterales para evitar recargas completas.
- Accesibilidad básica: contraste adecuado, estados de foco visibles, controles de audio accesibles.

## Métricas iniciales de éxito
- Tasa de finalización de onboarding (>80% de nuevos usuarios en 48h).
- Uso de Bóveda: porcentaje de miembros que copian al menos 1 blueprint en la primera semana.
- Tiempo promedio hasta primera generación en Prompt-Teca (<5 minutos tras login).
- Reproducciones de resumen ejecutivo por libro antes de descarga (>50%).
- Retención mensual de miembros activos y tasa de actualización de pago sin soporte manual.
