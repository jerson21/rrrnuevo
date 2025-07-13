/**
 * ============================================================================
 * UI COMPONENTS - COMPONENTES DE INTERFAZ DE USUARIO
 * ============================================================================
 * Componentes reutilizables para la interfaz de RespaldosChile
 * @version 1.0
 * @author RespaldosChile Team
 * @requires jQuery, Bootstrap 5, SweetAlert2 (opcional)
 * ============================================================================
 */

const UIComponents = (function($) {
    'use strict';

    // ========================================================================
    // CONFIGURACIÓN
    // ========================================================================
    
    const CONFIG = {
        toastDuration: 4000,
        loadingMinDuration: 500,
        animationDuration: 300,
        zIndexes: {
            loading: 9999,
            toast: 9998,
            modal: 1050
        },
        themes: {
            primary: '#007bff',
            success: '#28a745',
            warning: '#ffc107',
            danger: '#dc3545',
            info: '#17a2b8',
            dark: '#343a40',
            light: '#f8f9fa'
        }
    };

    let loadingCount = 0;
    let toastContainer = null;

    // ========================================================================
    // SISTEMA DE LOADING
    // ========================================================================
    
    function showLoading(show = true, message = 'Cargando...', options = {}) {
        if (show) {
            loadingCount++;
            
            if ($('#global-loading').length === 0) {
                const loadingHTML = createLoadingHTML(message, options);
                $('body').append(loadingHTML);
                
                // Fade in animation
                $('#global-loading').fadeIn(CONFIG.animationDuration);
            } else {
                $('#global-loading .loading-message').text(message);
            }
        } else {
            loadingCount = Math.max(0, loadingCount - 1);
            
            if (loadingCount === 0) {
                $('#global-loading').fadeOut(CONFIG.animationDuration, function() {
                    $(this).remove();
                });
            }
        }
    }

    function createLoadingHTML(message, options) {
        const theme = options.theme || 'primary';
        const size = options.size || 'normal';
        const backdrop = options.backdrop !== false;

        return `
            <div id="global-loading" class="loading-overlay ${backdrop ? 'with-backdrop' : ''}" 
                 style="z-index: ${CONFIG.zIndexes.loading};">
                <div class="loading-content loading-${size}">
                    <div class="loading-spinner spinner-${theme}">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                    <div class="loading-message text-${theme}">${message}</div>
                    ${options.progress ? '<div class="loading-progress"><div class="progress-bar"></div></div>' : ''}
                </div>
            </div>`;
    }

    function updateLoadingProgress(percentage) {
        const progressBar = $('.loading-progress .progress-bar');
        if (progressBar.length) {
            progressBar.css('width', `${percentage}%`);
        }
    }

    // ========================================================================
    // SISTEMA DE TOASTS
    // ========================================================================
    
    function showToast(message, type = 'info', options = {}) {
        ensureToastContainer();
        
        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const duration = options.duration || CONFIG.toastDuration;
        const position = options.position || 'top-end';
        
        const toastHTML = createToastHTML(toastId, message, type, options);
        
        if (position.includes('bottom')) {
            toastContainer.append(toastHTML);
        } else {
            toastContainer.prepend(toastHTML);
        }
        
        // Inicializar y mostrar el toast
        const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
            delay: duration,
            autohide: true
        });
        
        toastElement.show();
        
        // Configurar eventos
        setupToastEvents(toastId, toastElement, options);
        
        // Auto-remove después de la duración + buffer
        setTimeout(() => {
            $(`#${toastId}`).remove();
        }, duration + 1000);
        
        return toastId;
    }

    function createToastHTML(toastId, message, type, options) {
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle',
            primary: 'fas fa-bell'
        };
        
        const colors = {
            success: 'text-bg-success',
            error: 'text-bg-danger',
            warning: 'text-bg-warning',
            info: 'text-bg-info',
            primary: 'text-bg-primary'
        };

        const icon = icons[type] || icons.info;
        const colorClass = colors[type] || colors.info;
        const title = options.title || capitalizeFirst(type);
        const showCloseButton = options.closable !== false;
        const actionButton = options.action ? 
            `<button type="button" class="btn btn-sm btn-outline-light ms-2 toast-action" 
                     data-action="${options.action.callback || ''}">
                ${options.action.label || 'Acción'}
             </button>` : '';

        return `
            <div id="${toastId}" class="toast ${colorClass}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="${icon} me-2"></i>
                    <strong class="me-auto">${title}</strong>
                    <small class="text-muted">${formatTime(new Date())}</small>
                    ${showCloseButton ? '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' : ''}
                </div>
                <div class="toast-body">
                    ${message}
                    ${actionButton}
                </div>
            </div>`;
    }

    function ensureToastContainer() {
        if (!toastContainer || toastContainer.length === 0) {
            toastContainer = $('#toast-container');
            
            if (toastContainer.length === 0) {
                $('body').append(`
                    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" 
                         style="z-index: ${CONFIG.zIndexes.toast};">
                    </div>
                `);
                toastContainer = $('#toast-container');
            }
        }
    }

    function setupToastEvents(toastId, toastElement, options) {
        const toastEl = $(`#${toastId}`);
        
        // Evento de acción personalizada
        toastEl.find('.toast-action').on('click', function() {
            const actionCallback = $(this).data('action');
            if (typeof window[actionCallback] === 'function') {
                window[actionCallback]();
            } else if (options.action && typeof options.action.callback === 'function') {
                options.action.callback();
            }
            toastElement.hide();
        });
        
        // Eventos de ciclo de vida
        toastEl.on('shown.bs.toast', function() {
            if (options.onShow) options.onShow();
        });
        
        toastEl.on('hidden.bs.toast', function() {
            if (options.onHide) options.onHide();
        });
    }

    function clearAllToasts() {
        $('.toast').toast('hide');
        setTimeout(() => {
            if (toastContainer) {
                toastContainer.empty();
            }
        }, CONFIG.animationDuration);
    }

    // ========================================================================
    // SISTEMA DE ALERTAS
    // ========================================================================
    
    function showAlert(message, type = 'info', options = {}) {
        // Usar SweetAlert2 si está disponible
        if (typeof Swal !== 'undefined') {
            return showSweetAlert(message, type, options);
        } else {
            return showBootstrapAlert(message, type, options);
        }
    }

    function showSweetAlert(message, type, options) {
        const config = {
            title: options.title || getAlertTitle(type),
            text: message,
            icon: getSweetAlertIcon(type),
            confirmButtonText: options.confirmText || 'Aceptar',
            confirmButtonColor: CONFIG.themes[type] || CONFIG.themes.primary,
            allowOutsideClick: options.allowOutsideClick !== false,
            timer: options.timer || null,
            ...options.swalConfig
        };
        
        return Swal.fire(config);
    }

    function showBootstrapAlert(message, type, options) {
        const alertId = 'alert-' + Date.now();
        const alertHTML = createBootstrapAlertHTML(alertId, message, type, options);
        
        // Agregar al contenedor o al body
        const container = options.container || 'body';
        $(container).prepend(alertHTML);
        
        // Auto-hide si se especifica
        if (options.timer) {
            setTimeout(() => {
                $(`#${alertId}`).alert('close');
            }, options.timer);
        }
        
        return Promise.resolve({ isConfirmed: true });
    }

    function createBootstrapAlertHTML(alertId, message, type, options) {
        const alertTypes = {
            success: 'alert-success',
            error: 'alert-danger',
            warning: 'alert-warning',
            info: 'alert-info',
            primary: 'alert-primary'
        };
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle',
            primary: 'fas fa-bell'
        };
        
        const alertClass = alertTypes[type] || alertTypes.info;
        const icon = icons[type] || icons.info;
        const dismissible = options.dismissible !== false;
        
        return `
            <div id="${alertId}" class="alert ${alertClass} ${dismissible ? 'alert-dismissible' : ''} fade show" role="alert">
                <i class="${icon} me-2"></i>
                ${options.title ? `<strong>${options.title}</strong><br>` : ''}
                ${message}
                ${dismissible ? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' : ''}
            </div>`;
    }

    function getAlertTitle(type) {
        const titles = {
            success: 'Éxito',
            error: 'Error',
            warning: 'Advertencia',
            info: 'Información',
            primary: 'Aviso'
        };
        return titles[type] || titles.info;
    }

    function getSweetAlertIcon(type) {
        const icons = {
            success: 'success',
            error: 'error',
            warning: 'warning',
            info: 'info',
            primary: 'info'
        };
        return icons[type] || icons.info;
    }

    // ========================================================================
    // SISTEMA DE CONFIRMACIÓN
    // ========================================================================
    
    function showConfirm(message, title = 'Confirmar', options = {}) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Sí, continuar',
                cancelButtonText: options.cancelText || 'Cancelar',
                confirmButtonColor: options.confirmColor || CONFIG.themes.primary,
                cancelButtonColor: options.cancelColor || CONFIG.themes.secondary,
                reverseButtons: options.reverseButtons || false,
                ...options.swalConfig
            });
        } else {
            const result = confirm(`${title}\n\n${message}`);
            return Promise.resolve({ isConfirmed: result });
        }
    }

    function showCustomConfirm(config) {
        const modalId = 'confirm-modal-' + Date.now();
        const modalHTML = createConfirmModalHTML(modalId, config);
        
        $('body').append(modalHTML);
        
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        
        return new Promise((resolve) => {
            $(`#${modalId}`).on('hidden.bs.modal', function() {
                $(this).remove();
            });
            
            $(`#${modalId} .btn-confirm`).on('click', function() {
                modal.hide();
                resolve({ isConfirmed: true, value: getModalInputValue(modalId) });
            });
            
            $(`#${modalId} .btn-cancel`).on('click', function() {
                modal.hide();
                resolve({ isConfirmed: false });
            });
            
            modal.show();
        });
    }

    function createConfirmModalHTML(modalId, config) {
        return `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${config.title || 'Confirmar'}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${config.html || config.text || ''}
                            ${config.input ? createModalInput(config.input) : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-cancel">
                                ${config.cancelText || 'Cancelar'}
                            </button>
                            <button type="button" class="btn btn-primary btn-confirm">
                                ${config.confirmText || 'Confirmar'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    function createModalInput(inputConfig) {
        const type = inputConfig.type || 'text';
        const placeholder = inputConfig.placeholder || '';
        const value = inputConfig.value || '';
        
        if (type === 'select') {
            let optionsHTML = '';
            for (const [key, label] of Object.entries(inputConfig.options || {})) {
                optionsHTML += `<option value="${key}">${label}</option>`;
            }
            return `<select class="form-select modal-input" required>${optionsHTML}</select>`;
        } else if (type === 'textarea') {
            return `<textarea class="form-control modal-input" placeholder="${placeholder}" rows="3">${value}</textarea>`;
        } else {
            return `<input type="${type}" class="form-control modal-input" placeholder="${placeholder}" value="${value}">`;
        }
    }

    function getModalInputValue(modalId) {
        const input = $(`#${modalId} .modal-input`);
        return input.length ? input.val() : null;
    }

    // ========================================================================
    // SISTEMA DE MODALES
    // ========================================================================
    
    function createModal(config) {
        const modalId = config.id || 'modal-' + Date.now();
        const modalHTML = createModalHTML(modalId, config);
        
        $('body').append(modalHTML);
        
        const modal = new bootstrap.Modal(document.getElementById(modalId), {
            backdrop: config.backdrop !== false,
            keyboard: config.keyboard !== false,
            focus: config.focus !== false
        });
        
        // Setup events
        if (config.onShow) {
            $(`#${modalId}`).on('shown.bs.modal', config.onShow);
        }
        
        if (config.onHide) {
            $(`#${modalId}`).on('hidden.bs.modal', config.onHide);
        }
        
        // Auto-remove when hidden
        $(`#${modalId}`).on('hidden.bs.modal', function() {
            if (config.autoRemove !== false) {
                $(this).remove();
            }
        });
        
        return {
            modal: modal,
            element: $(`#${modalId}`),
            show: () => modal.show(),
            hide: () => modal.hide(),
            update: (newConfig) => updateModal(modalId, newConfig)
        };
    }

    function createModalHTML(modalId, config) {
        const size = config.size ? `modal-${config.size}` : '';
        const centered = config.centered ? 'modal-dialog-centered' : '';
        const scrollable = config.scrollable ? 'modal-dialog-scrollable' : '';
        
        return `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog ${size} ${centered} ${scrollable}">
                    <div class="modal-content">
                        ${config.showHeader !== false ? createModalHeader(config) : ''}
                        <div class="modal-body">
                            ${config.content || config.body || ''}
                        </div>
                        ${config.showFooter !== false ? createModalFooter(config) : ''}
                    </div>
                </div>
            </div>`;
    }

    function createModalHeader(config) {
        return `
            <div class="modal-header">
                <h5 class="modal-title">${config.title || 'Modal'}</h5>
                ${config.closable !== false ? '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' : ''}
            </div>`;
    }

    function createModalFooter(config) {
        if (!config.buttons) {
            return `
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>`;
        }
        
        let buttonsHTML = '';
        config.buttons.forEach(button => {
            const btnClass = button.class || 'btn-primary';
            const btnAction = button.action || 'data-bs-dismiss="modal"';
            buttonsHTML += `<button type="button" class="btn ${btnClass}" ${btnAction}>${button.text}</button>`;
        });
        
        return `<div class="modal-footer">${buttonsHTML}</div>`;
    }

    function updateModal(modalId, newConfig) {
        const modal = $(`#${modalId}`);
        
        if (newConfig.title) {
            modal.find('.modal-title').text(newConfig.title);
        }
        
        if (newConfig.content || newConfig.body) {
            modal.find('.modal-body').html(newConfig.content || newConfig.body);
        }
        
        if (newConfig.footer) {
            modal.find('.modal-footer').html(newConfig.footer);
        }
    }

    // ========================================================================
    // SISTEMA DE VALIDACIÓN DE FORMULARIOS
    // ========================================================================
    
    function validateForm(formSelector, rules = {}) {
        const form = $(formSelector);
        let isValid = true;
        const errors = [];
        
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        
        // Validar cada campo según las reglas
        Object.keys(rules).forEach(fieldName => {
            const field = form.find(`[name="${fieldName}"]`);
            const fieldRules = rules[fieldName];
            const value = field.val();
            
            const fieldErrors = validateField(value, fieldRules, fieldName);
            
            if (fieldErrors.length > 0) {
                isValid = false;
                errors.push(...fieldErrors);
                
                field.addClass('is-invalid');
                field.after(`<div class="invalid-feedback">${fieldErrors.join('<br>')}</div>`);
            }
        });
        
        return {
            isValid: isValid,
            errors: errors
        };
    }

    function validateField(value, rules, fieldName) {
        const errors = [];
        
        if (rules.required && (!value || value.trim() === '')) {
            errors.push(`${rules.label || fieldName} es requerido`);
            return errors; // Si es requerido y está vacío, no validar el resto
        }
        
        if (value && rules.minLength && value.length < rules.minLength) {
            errors.push(`${rules.label || fieldName} debe tener al menos ${rules.minLength} caracteres`);
        }
        
        if (value && rules.maxLength && value.length > rules.maxLength) {
            errors.push(`${rules.label || fieldName} no puede tener más de ${rules.maxLength} caracteres`);
        }
        
        if (value && rules.pattern && !rules.pattern.test(value)) {
            errors.push(rules.patternMessage || `${rules.label || fieldName} tiene un formato inválido`);
        }
        
        if (value && rules.custom && typeof rules.custom === 'function') {
            const customResult = rules.custom(value);
            if (customResult !== true) {
                errors.push(customResult || `${rules.label || fieldName} no es válido`);
            }
        }
        
        return errors;
    }

    // ========================================================================
    // FUNCIONES UTILITARIAS
    // ========================================================================
    
    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function formatTime(date) {
        return date.toLocaleTimeString('es-CL', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function createProgressBar(container, options = {}) {
        const progressId = 'progress-' + Date.now();
        const progressHTML = `
            <div id="${progressId}" class="progress mb-3" style="height: ${options.height || '20px'};">
                <div class="progress-bar progress-bar-striped ${options.animated ? 'progress-bar-animated' : ''}" 
                     role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    ${options.showText ? '0%' : ''}
                </div>
            </div>`;
        
        $(container).append(progressHTML);
        
        return {
            update: (percentage, text) => {
                const progressBar = $(`#${progressId} .progress-bar`);
                progressBar.css('width', `${percentage}%`);
                progressBar.attr('aria-valuenow', percentage);
                if (options.showText) {
                    progressBar.text(text || `${percentage}%`);
                }
            },
            remove: () => $(`#${progressId}`).remove()
        };
    }

    // ========================================================================
    // INICIALIZACIÓN Y CSS
    // ========================================================================
    
    function init() {
        injectCSS();
        setupGlobalEvents();
        console.log('✅ UIComponents inicializado correctamente');
    }

    function injectCSS() {
        if ($('#ui-components-styles').length === 0) {
            $('<style id="ui-components-styles">')
                .prop("type", "text/css")
                .html(`
                    .loading-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: rgba(0,0,0,0.5);
                        backdrop-filter: blur(2px);
                    }
                    
                    .loading-overlay.with-backdrop {
                        background: rgba(0,0,0,0.7);
                    }
                    
                    .loading-content {
                        background: white;
                        padding: 2rem;
                        border-radius: 12px;
                        text-align: center;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                        min-width: 200px;
                    }
                    
                    .loading-content.loading-small {
                        padding: 1rem;
                        min-width: 150px;
                    }
                    
                    .loading-content.loading-large {
                        padding: 3rem;
                        min-width: 300px;
                    }
                    
                    .loading-spinner {
                        margin-bottom: 1rem;
                    }
                    
                    .loading-message {
                        font-weight: 500;
                        margin-bottom: 0;
                    }
                    
                    .loading-progress {
                        width: 100%;
                        height: 4px;
                        background: #e9ecef;
                        border-radius: 2px;
                        margin-top: 1rem;
                        overflow: hidden;
                    }
                    
                    .loading-progress .progress-bar {
                        height: 100%;
                        background: var(--bs-primary);
                        transition: width 0.3s ease;
                    }
                    
                    .toast-container {
                        max-width: 350px;
                    }
                    
                    .toast {
                        border: none;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                    }
                    
                    .spinner-primary .spinner-border { color: #007bff; }
                    .spinner-success .spinner-border { color: #28a745; }
                    .spinner-warning .spinner-border { color: #ffc107; }
                    .spinner-danger .spinner-border { color: #dc3545; }
                    .spinner-info .spinner-border { color: #17a2b8; }
                    
                    .text-primary { color: #007bff !important; }
                    .text-success { color: #28a745 !important; }
                    .text-warning { color: #ffc107 !important; }
                    .text-danger { color: #dc3545 !important; }
                    .text-info { color: #17a2b8 !important; }
                `)
                .appendTo("head");
        }
    }

    function setupGlobalEvents() {
        // Cerrar toasts con Escape
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape') {
                $('.toast').toast('hide');
            }
        });
        
        // Eventos globales para formularios
        $(document).on('submit', 'form[data-validate]', function(e) {
            const form = $(this);
            const rules = form.data('validation-rules');
            
            if (rules) {
                const validation = validateForm(form, rules);
                if (!validation.isValid) {
                    e.preventDefault();
                    showAlert('Por favor corrige los errores en el formulario', 'warning');
                }
            }
        });
    }

    // ========================================================================
    // API PÚBLICA
    // ========================================================================
    
    return {
        // Inicialización
        init: init,
        
        // Loading
        showLoading: showLoading,
        updateLoadingProgress: updateLoadingProgress,
        
        // Toasts
        showToast: showToast,
        clearAllToasts: clearAllToasts,
        
        // Alertas
        showAlert: showAlert,
        
        // Confirmación
        showConfirm: showConfirm,
        showCustomConfirm: showCustomConfirm,
        
        // Modales
        createModal: createModal,
        updateModal: updateModal,
        
        // Validación
        validateForm: validateForm,
        
        // Utilidades
        createProgressBar: createProgressBar,
        debounce: debounce,
        
        // Acceso a configuración
        getConfig: () => Object.freeze({...CONFIG})
    };

})(jQuery);

// ========================================================================
// AUTO-INICIALIZACIÓN
// ========================================================================

$(document).ready(function() {
    UIComponents.init();
});

// Exportar al namespace global
window.UIComponents = UIComponents;