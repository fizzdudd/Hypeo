import './bootstrap';
import {
    createIcons, ArrowRight, Check, Download, CheckCircle2, ChevronLeft, ChevronRight, Coffee, Copy, Eye, Facebook, FileX,
    Heart, Info, Instagram, Layers, Linkedin, LogOut, MapPin, MessageCircle, MessageSquare, MoreHorizontal, Music2,
    Package, PackagePlus, Pencil, Plus, RefreshCw, Repeat2, RotateCcw, Search, SearchX, Send, Share2, ShieldCheck,
    Sparkles, ThumbsUp, Trash2, User, WifiOff, X,
} from 'lucide';

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

// Solo los íconos que usan las vistas (mantiene el bundle liviano)
const icons = {
    ArrowRight, Check, Download, CheckCircle2, ChevronLeft, ChevronRight, Coffee, Copy, Eye, Facebook, FileX, Heart, Info,
    Instagram, Layers, Linkedin, LogOut, MapPin, MessageCircle, MessageSquare, MoreHorizontal, Music2, Package,
    PackagePlus, Pencil, Plus, RefreshCw, Repeat2, RotateCcw, Search, SearchX, Send, Share2, ShieldCheck, Sparkles,
    ThumbsUp, Trash2, User, WifiOff, X,
};

const pintarIconos = () => createIcons({ icons, attrs: { 'stroke-width': 1.75 } });

/* Copiar al portapapeles: data-copy="#id" o data-copy-text="..." */
function initCopiar() {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-copy], [data-copy-text]');
        if (!btn) return;
        const texto = btn.dataset.copyText ?? $(btn.dataset.copy)?.innerText ?? '';
        try {
            await navigator.clipboard.writeText(texto.trim());
        } catch {
            const ta = Object.assign(document.createElement('textarea'), { value: texto.trim() });
            document.body.append(ta); ta.select(); document.execCommand('copy'); ta.remove();
        }
        const label = $('[data-copy-label]', btn);
        btn.classList.add('copiado');
        if (label) {
            const antes = label.textContent;
            label.textContent = '¡Copiado!';
            setTimeout(() => { label.textContent = antes; btn.classList.remove('copiado'); }, 1600);
        } else {
            toast('Copiado al portapapeles');
            setTimeout(() => btn.classList.remove('copiado'), 1600);
        }
    });
}

function toast(msg) {
    const t = Object.assign(document.createElement('div'), {
        className: 'fixed left-1/2 top-4 z-50 -translate-x-1/2 rounded-xl bg-ink px-4 py-2.5 text-sm font-semibold text-paper shadow-lg',
        textContent: msg,
        role: 'status',
    });
    document.body.append(t);
    setTimeout(() => t.remove(), 1600);
}

/* Hojas inferiores con <dialog> */
function initDialogs() {
    document.addEventListener('click', (e) => {
        const abrir = e.target.closest('[data-open-dialog]');
        if (abrir) {
            e.preventDefault();
            $('#' + abrir.dataset.openDialog)?.showModal();
        }
        const cerrar = e.target.closest('[data-close-dialog]');
        if (cerrar) cerrar.closest('dialog')?.close();
        if (e.target.matches('dialog.sheet')) e.target.close(); // clic en el fondo
    });
}

/* Botones de envío de un form, incluidos los que están fuera con form="id" */
const botonesDe = (form) => [
    ...form.querySelectorAll('button[type=submit]'),
    ...(form.id ? $$(`button[form="${form.id}"]`) : []),
];

/* 07 · Pantalla "Generando": se muestra mientras el servidor llama a Groq */
function initGenerando() {
    $$('form[data-generar]').forEach((form) => {
        form.addEventListener('submit', () => {
            const overlay = $('#generando');
            if (!overlay) return;
            const red = form.querySelector('[name=red]:checked')?.dataset.label ?? form.dataset.red ?? '';
            const tono = form.querySelector('[name=tono]:checked')?.dataset.label ?? form.dataset.tono ?? '';
            $('[data-gen-sel]', overlay).textContent = `${red} · ${tono}`.toUpperCase();
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            const pasos = $$('[data-paso]', overlay);
            let i = 0;
            const avanzar = () => {
                pasos.forEach((p, j) => {
                    p.dataset.estado = j < i ? 'hecho' : j === i ? 'activo' : 'pendiente';
                });
                if (i < pasos.length - 1) { i++; setTimeout(avanzar, 1100 + Math.random() * 600); }
            };
            avanzar();
            botonesDe(form).forEach((b) => (b.disabled = true));
        });
    });
    // Si el usuario vuelve con "atrás", ocultamos la capa
    window.addEventListener('pageshow', (e) => {
        if (!e.persisted) return;
        $('#generando')?.classList.add('hidden');
        $$('form[data-generar]').forEach((f) => botonesDe(f).forEach((b) => (b.disabled = false)));
    });
}

/* Botón del resumen del botón de generar: "IG · CERCANO" */
function initResumenSeleccion() {
    const form = $('form[data-generar][data-resumen]');
    if (!form) return;
    const salida = $('[data-resumen-sel]');
    const actualizar = () => {
        const red = form.querySelector('[name=red]:checked')?.dataset.abrev ?? '—';
        const tono = form.querySelector('[name=tono]:checked')?.dataset.label ?? '—';
        salida.textContent = `${red} · ${tono}`.toUpperCase();
    };
    form.addEventListener('change', actualizar);
    actualizar();
}

/* Contadores de caracteres */
function initContadores() {
    $$('[data-counter]').forEach((campo) => {
        const salida = $(campo.dataset.counter);
        const max = Number(campo.getAttribute('maxlength')) || 0;
        const fmt = (n) => n.toLocaleString('es-CL');
        const actualizar = () => {
            salida.textContent = max ? `${fmt(campo.value.length)} / ${fmt(max)}` : fmt(campo.value.length);
        };
        campo.addEventListener('input', actualizar);
        actualizar();
    });
}

/* Pestañas simples: [data-tabs] con botones [data-tab] y paneles [data-panel] */
function initTabs() {
    $$('[data-tabs]').forEach((grupo) => {
        const botones = $$('[data-tab]', grupo);
        const activar = (nombre) => {
            botones.forEach((b) => b.setAttribute('aria-selected', b.dataset.tab === nombre));
            $$('[data-panel]', grupo).forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== nombre));
            const extra = grupo.dataset.tabsTarget;
            if (extra) $$(`${extra} [data-panel]`).forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== nombre));
        };
        botones.forEach((b) => b.addEventListener('click', () => {
            activar(b.dataset.tab);
            b.closest('form')?.querySelector(`[data-panel="${b.dataset.tab}"] textarea`)?.focus();
        }));
        activar(botones.find((b) => b.getAttribute('aria-selected') === 'true')?.dataset.tab ?? botones[0]?.dataset.tab);
    });
}

/* Intereses como etiquetas: escribe y presiona Enter o coma */
function initEtiquetas() {
    $$('[data-tags]').forEach((caja) => {
        const oculto = $('input[type=hidden]', caja);
        const entrada = $('input[type=text]', caja);
        const lista = $('[data-tags-list]', caja);
        let tags = oculto.value.split(',').map((t) => t.trim()).filter(Boolean);

        const pintar = () => {
            lista.innerHTML = '';
            tags.forEach((t, i) => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'rounded-xl bg-hype-soft px-3 py-[7px] text-sm font-medium text-hype-dark hover:bg-[#f9d6c6]';
                chip.textContent = `${t} ×`;
                chip.setAttribute('aria-label', `Quitar ${t}`);
                chip.addEventListener('click', () => { tags.splice(i, 1); pintar(); });
                lista.append(chip);
            });
            oculto.value = tags.join(',');
        };
        const agregar = () => {
            entrada.value.split(',').map((t) => t.trim().toLowerCase()).filter(Boolean).forEach((t) => {
                if (!tags.includes(t) && tags.length < 8) tags.push(t);
            });
            entrada.value = '';
            pintar();
        };
        entrada.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); agregar(); }
            if (e.key === 'Backspace' && !entrada.value && tags.length) { tags.pop(); pintar(); }
        });
        entrada.addEventListener('blur', agregar);
        entrada.form?.addEventListener('submit', agregar);
        pintar();
    });
}

/* Precio con separador de miles chileno */
function initPrecio() {
    $$('[data-precio]').forEach((campo) => {
        const fmt = () => {
            const n = campo.value.replace(/\D/g, '');
            campo.value = n ? Number(n).toLocaleString('es-CL') : '';
        };
        campo.addEventListener('input', fmt);
        fmt();
    });
}

/* Registro: barra de fuerza y coincidencia de contraseñas */
function initPassword() {
    const pass = $('[data-strength]');
    if (pass) {
        const barras = $$('[data-bar]');
        const texto = $('[data-strength-text]');
        const eval_ = () => {
            const v = pass.value;
            let s = 0;
            if (v.length >= 8) s++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
            if (/\d/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v) || v.length >= 12) s++;
            barras.forEach((b, i) => b.classList.toggle('bg-hype', i < s));
            barras.forEach((b, i) => b.classList.toggle('bg-line', i >= s));
            texto.textContent = v.length === 0 ? 'Mínimo 8 caracteres'
                : v.length < 8 ? 'Muy corta · mínimo 8 caracteres'
                : ['Débil', 'Débil', 'Aceptable', 'Buena', 'Excelente'][s] + ' · mínimo 8 caracteres';
        };
        pass.addEventListener('input', eval_);
        eval_();
    }
    const conf = $('[data-confirm]');
    if (conf) {
        const ok = $('[data-confirm-ok]');
        const check = () => ok.classList.toggle('invisible', !conf.value || conf.value !== $(conf.dataset.confirm).value);
        conf.addEventListener('input', check);
        $(conf.dataset.confirm).addEventListener('input', check);
        check();
    }
    $$('[data-toggle-pass]').forEach((b) => b.addEventListener('click', () => {
        const campo = $(b.dataset.togglePass);
        campo.type = campo.type === 'password' ? 'text' : 'password';
        b.setAttribute('aria-pressed', campo.type === 'text');
    }));
}

/* 13 · Reintentar con cuenta regresiva (error 429) */
function initCuentaRegresiva() {
    $$('[data-countdown]').forEach((btn) => {
        let s = Number(btn.dataset.countdown);
        const salida = $('[data-countdown-text]', btn);
        if (!s) return;
        btn.disabled = true;
        const tick = () => {
            if (s <= 0) { btn.disabled = false; salida.textContent = ''; return; }
            salida.textContent = ` · ${Math.floor(s / 60)}:${String(s % 60).padStart(2, '0')}`;
            s--; setTimeout(tick, 1000);
        };
        tick();
    });
}

/* Imagen generada por IA: estado de carga / error */
function initImagenes() {
    $$('[data-imagen]').forEach((caja) => {
        const img = $('img', caja);
        if (!img) return;
        const ok = () => (caja.dataset.estado = 'ok');
        const error = () => (caja.dataset.estado = 'error');
        if (img.complete) { img.naturalWidth ? ok() : error(); return; }
        img.addEventListener('load', ok);
        img.addEventListener('error', error);
    });
}

/* "ver más" / "ver menos" */
function initDesplegables() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-toggle-mas]');
        if (!btn) return;
        const caja = btn.closest('[data-desplegable]');
        caja.dataset.abierto = caja.dataset.abierto === 'true' ? 'false' : 'true';
    });
}

/* Aviso sin conexión */
function initConexion() {
    const aviso = $('#sin-conexion');
    if (!aviso) return;
    const act = () => aviso.classList.toggle('hidden', navigator.onLine);
    window.addEventListener('online', act);
    window.addEventListener('offline', act);
    act();
}

document.addEventListener('DOMContentLoaded', () => {
    pintarIconos();
    initCopiar();
    initDialogs();
    initGenerando();
    initResumenSeleccion();
    initContadores();
    initTabs();
    initEtiquetas();
    initPrecio();
    initPassword();
    initCuentaRegresiva();
    initConexion();
    initImagenes();
    initDesplegables();
});
