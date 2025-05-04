// Funciones para interactuar con Tailwind CSS

// Función para efectos de hover en los enlaces
document.addEventListener('DOMContentLoaded', function() {
  // Actualización de la fecha usando JavaScript
  // Si decides usar esta opción, descomenta el elemento en index.php
  const actualizarFechaHora = () => {
    const ahora = new Date();
    
    // Formatear fecha en formato español (día-mes-año hora:minutos)
    const dia = String(ahora.getDate()).padStart(2, '0');
    const mes = String(ahora.getMonth() + 1).padStart(2, '0');
    const anio = ahora.getFullYear();
    const hora = String(ahora.getHours()).padStart(2, '0');
    const minutos = String(ahora.getMinutes()).padStart(2, '0');
    
    const fechaFormateada = `${dia}-${mes}-${anio} ${hora}:${minutos}`;
    
    // Actualizar el elemento si existe
    const elementoFecha = document.getElementById('fechaActualizacion');
    if (elementoFecha) {
      elementoFecha.textContent = fechaFormateada;
    }
  };
  
  // Ejecutar al cargar la página
  actualizarFechaHora();
  
  // Manejar cierre de la notificación
  const btnCloseNotification = document.getElementById('close-notification');
  if (btnCloseNotification) {
    btnCloseNotification.addEventListener('click', function() {
      const notification = this.closest('.bg-yellow-50');
      if (notification) {
        // Añadir animación de desvanecimiento
        notification.style.transition = 'opacity 0.5s';
        notification.style.opacity = '0';
        
        // Eliminar el elemento después de la animación
        setTimeout(() => {
          notification.remove();
        }, 500);
        
        // Guardar en localStorage que la notificación fue cerrada
        localStorage.setItem('notificationClosed', 'true');
      }
    });
    
    // Verificar si la notificación ya fue cerrada anteriormente
    if (localStorage.getItem('notificationClosed') === 'true') {
      const notification = document.querySelector('.bg-yellow-50');
      if (notification) {
        notification.style.display = 'none';
      }
    }
  }
  
  // Animación para enlaces con clases de Tailwind
  document.querySelectorAll('a').forEach(a => {
    // Si no tiene clases de Tailwind para hover, los añadimos
    if (!a.classList.contains('hover:text-blue-800')) {
      a.classList.add('transition-colors', 'duration-200', 'hover:text-blue-800');
    }
  });

  // Función para manejar desplazamiento suave
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);
      
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 100,
          behavior: 'smooth'
        });
      }
    });
  });

  // Funcionalidad de acordeón para las convocatorias CAS
  document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
      // Obtener el elemento padre (accordion-item)
      const item = header.closest('.accordion-item');
      
      // Obtener el contenido del acordeón
      const content = item.querySelector('.accordion-content');
      
      // Obtener el ícono para rotarlo
      const icon = header.querySelector('.accordion-icon');
      
      // Abrir/cerrar este acordeón
      if (content.classList.contains('hidden')) {
        // Abrir este acordeón
        content.classList.remove('hidden');
        content.classList.add('block');
        
        // Rotar el ícono
        if (icon) {
          icon.classList.add('rotate-180');
        }
      } else {
        // Cerrar este acordeón
        content.classList.add('hidden');
        content.classList.remove('block');
        
        // Restaurar el ícono
        if (icon) {
          icon.classList.remove('rotate-180');
        }
      }
    });
  });

  // Inicializar: mostrar el primer acordeón por defecto (opcional)
  const firstItem = document.querySelector('.accordion-item');
  if (firstItem) {
    const firstContent = firstItem.querySelector('.accordion-content');
    const firstIcon = firstItem.querySelector('.accordion-icon');
    
    if (firstContent) {
      firstContent.classList.remove('hidden');
      firstContent.classList.add('block');
      
      if (firstIcon) {
        firstIcon.classList.add('rotate-180');
      }
    }
  }
});
