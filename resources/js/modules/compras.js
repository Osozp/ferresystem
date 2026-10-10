let purchaseItems = [];

const detailsContainer = document.getElementById("purchase-details-container");
const totalDisplay = document.getElementById("total-display");
const totalInput = document.getElementById("total-input");

document.addEventListener("click", (e) => {
    const button = e.target?.closest('[data-action="btn-add-product"]');
    if (button && !button.disabled) {
        const id = button.dataset.id;
        const code = button.dataset.code;
        const description = button.dataset.description;

        button.disabled = true;
        button.classList.add("opacity-50", "cursor-not-allowed", "border", "border-gray-300", "!bg-transparent", "text-gray-400" );
        button.textContent = "Agregado";

        addItem(id, code, description);
    }
});

function addItem(id, code, description) {
    const existingItem = purchaseItems.find((item) => item.id === id);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        purchaseItems.push({
            id: id,
            code: code,
            description: description,
            quantity: 1,
            price: 0.0,
        });
    }

    renderDetails();
}

// Escuchar clic para eliminar un ítem del carrito
if (detailsContainer) {
    detailsContainer.addEventListener("click", (e) => {
        const removeBtn = e.target?.closest(".btn-remove-item");

        if (removeBtn) {
            const productId = removeBtn.dataset.id;

            // Filtrar del array
            purchaseItems = purchaseItems.filter(
                (item) => item.id !== productId,
            );

            // Reactivar el botón de agregar en la tabla de productos
            const originalButton = document.querySelector(
                `[data-action="btn-add-product"][data-id="${productId}"]`,
            );
            if (originalButton) {
                originalButton.disabled = false;
                originalButton.classList.remove(
                    "opacity-50",
                    "cursor-not-allowed",
                    "border", "border-gray-300", "!bg-transparent","text-gray-400"
                );
                originalButton.textContent = "Agregar";
            }

            renderDetails();
        }
    });
}

// ==========================================
// 3. RENDERIZADO Y CÁLCULO DE TOTALES
// ==========================================

// Renderizar todas las filas de la compra dinámicamente
function renderDetails() {
    if (!detailsContainer) return;

    detailsContainer.innerHTML = "";
    let grandTotal = 0;

    purchaseItems.forEach((item, index) => {
        const itemTotal = item.quantity * item.price;
        grandTotal += itemTotal;

        const sellingPrice = item.selling_price ?? item.price ?? 0;

        const row = document.createElement("div");
        row.classList.add("mb-2");
        // row.innerHTML =`<flux:table.cell>
        //                       ${item.description}

        //                         <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
        //                     </flux:table.cell>

        //                     <flux:table.cell>
        //                         <flux:input/>
        //                     </flux:table.cell>

        //                     <flux:table.cell>
        //                          <flux:input/>
        //                     </flux:table.cell>

        //                     <flux:table.cell>
        //                          <flux:input/>
        //                     </flux:table.cell>

        //                     <flux:table.cell>
        //                         Subtotla
        //                     </flux:table.cell>`;
        // row.innerHTML = `<div class="grid grid-cols-5 gap-2 items-end py-3">
        //                     <div>
        //                         <p class="text-sm font-semibold text-gray-800 truncate">${item.description}</p>
        //                         <span class="text-xs font-mono text-gray-500">Cód: ${item.code}</span>
        //                         <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
        //                     </div>
        //                     <div>
        //                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5">
        //                             <span class="text-xs font-medium text-gray-500">Bs.</span>
        //                         </div>
        //                         <input type="number"
        //                                name="items[${index}][quantity]"
        //                             class="input-quantity block w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 text-right text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        //                             data-index="${index}" value="${item.quantity}"
        //                             min = "1"
        //                             placeholder="Cant.">
        //                     </div>
        //                     <div>
        //                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5">
        //                             <span class="text-xs font-medium text-gray-500">Bs.</span>
        //                         </div>
        //                         <input type="number" step="0.01"
        //                                 name="items[${index}][price]"
        //                                 class="input-price block w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 text-right text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        //                                 data-index="${index}" value="${item.price}" placeholder="0.00">
        //                     </div>
        //                     <div>
        //                         <span class="text-[10px] font-semibold text-blue-600 block leading-tight">P.
        //                             Venta</span>
        //                         <div class="relative mt-0.5">
        //                             <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
        //                                 <span class="text-xs font-medium text-blue-600">Bs.</span>
        //                             </div>
        //                             <input type="number" step="0.01" name="items[${index}][selling_price]"
        //                                 class="input-selling-price block w-full rounded-md border border-blue-200 bg-blue-50/50 py-1 pl-7 pr-1.5 text-right text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        //                                 data-index="${index}" value="${sellingPrice}" placeholder="0.00">
        //                         </div>
        //                     </div>
        //                     <div class="flex items-end">
        //                         <div>
        //                             <span class="text-xs text-gray-400 block font-normal">Subtotal</span>
        //                             <span class="text-sm font-semibold text-gray-800 subtotal-display"
        //                                 data-index="${index}">
        //                                 ${itemTotal.toFixed(2)}
        //                             </span>
        //                         </div>
        //                         <button type="button"
        //                             class="btn-remove-item flex h-8 w-8 items-center justify-center rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors"
        //                             data-id="${item.id}" title="Eliminar producto">
        //                             <svg class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24"
        //                                 stroke-width="2" stroke="currentColor">
        //                                 <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        //                             </svg>
        //                         </button>
        //                     </div>
        //                 </div>`;


         row.innerHTML = `
                <div class="item-row grid grid-cols-5 items-end gap-3 py-3">

                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-zinc-300 truncate">${item.description}</p>
                        <span class="text-xs font-mono text-gray-500 dark:text-zinc-400">Cód: ${item.code}</span>
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    </div>


                    <!-- Cantidad -->
                    <div class="">
                        <label class="sr-only">Cantidad</label>
                        <input type="number"
                            name="items[${index}][quantity]"
                            class="input-quantity block w-full rounded-md border border-zinc-200 dark:border-white/10 bg-gray-50 dark:bg-white/10 py-1.5 dark:text-zinc-300 text-center text-sm font-medium text-zinc-700 "
                            data-index="${index}"
                            value="${item.quantity}"
                            min="1"
                            placeholder="Cant.">
                    </div>

                    <!-- Precio Unitario -->
                    <div class="">
                       <div class="relative ">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                                <span class="text-xs font-medium text-gray-600 dark:text-zinc-300">Bs.</span>
                            </div>

                            <input type="number"
                                step="0.01"
                                name="items[${index}][price]"
                                class="input-price block w-full rounded-md border border-zinc-200 dark:border-white/10 bg-gray-50 dark:bg-white/10 py-1.5 dark:text-zinc-300 text-right text-sm font-medium text-zinc-700 "
                                data-index="${index}"
                                value="${item.price}"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Nuevo Precio de Venta al Público -->
                    <div class="mb-0.4">
                        <span class="text-[10px] font-semibold text-blue-600 dark:text-amber-500 block leading-tight">P. Venta</span>
                        <div class="relative mt-0.5">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                                <span class="text-xs font-medium text-blue-600 dark:text-amber-500">Bs.</span>
                            </div>
                            <input type="number"
                                step="0.01"
                                name="items[${index}][selling_price]"
                                class="input-selling-price block w-full rounded-md border border-blue-200 dark:border-amber-500 bg-blue-50/50 dark:bg-white/10 py-1 pl-7 pr-1.5 text-right text-sm font-medium text-zinc-700 dark:text-zinc-300"
                                data-index="${index}"
                                value="${sellingPrice}"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Subtotal -->
                    <div class="flex gap-1 items-end">

                       <div>
                            <span class="text-xs text-gray-400 block font-normal">Subtotal</span>
                            <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 subtotal-display mt-0.5" data-index="${index}">
                                ${itemTotal.toFixed(2)}
                            </span>
                       </div>

                         <!-- Botón Eliminar -->
                        <button type="button"
                            class="btn-remove-item flex h-8 w-8 items-center justify-center rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors"
                            data-id="${item.id}"
                            title="Eliminar producto">
                            <svg class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>



                </div>
            `;


        // row.innerHTML = `
        //         <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-xs hover:border-gray-300 transition-colors">
        //             <div class="flex-1 min-w-0">
        //                 <p class="text-sm font-semibold text-gray-800 truncate">${item.description}</p>
        //                 <span class="text-xs font-mono text-gray-500">Cód: ${item.code}</span>
        //                 <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
        //             </div>

        //             <div class="flex items-end gap-3">
        //                 <!-- Cantidad -->
        //                 <div class="w-16">
        //                     <label class="sr-only">Cantidad</label>
        //                     <input type="number"
        //                         name="items[${index}][quantity]"
        //                         class="input-quantity block w-full rounded-md border border-gray-300 bg-gray-50 p-1.5 text-center text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        //                         data-index="${index}"
        //                         value="${item.quantity}"
        //                         min="1"
        //                         placeholder="Cant.">
        //                 </div>

        //                 <!-- Precio Unitario -->
        //                 <div class="relative w-20">
        //                     <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5">
        //                         <span class="text-xs font-medium text-gray-500">Bs.</span>
        //                     </div>
        //                     <input type="number"
        //                         step="0.01"
        //                         name="items[${index}][price]"
        //                         class="input-price block w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-8 pr-2 text-right text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        //                         data-index="${index}"
        //                         value="${item.price}"
        //                         placeholder="0.00">
        //                 </div>
        //                 <!-- Nuevo Precio de Venta al Público -->
        //                 <div class="relative w-20">
        //                     <span class="text-[10px] font-semibold text-blue-600 block leading-tight">P. Venta</span>
        //                     <div class="relative mt-0.5">
        //                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
        //                             <span class="text-xs font-medium text-blue-600">Bs.</span>
        //                         </div>
        //                         <input type="number"
        //                             step="0.01"
        //                             name="items[${index}][selling_price]"
        //                             class="input-selling-price block w-full rounded-md border border-blue-200 bg-blue-50/50 py-1 pl-7 pr-1.5 text-right text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        //                             data-index="${index}"
        //                             value="${sellingPrice}"
        //                             placeholder="0.00">
        //                     </div>
        //                 </div>

        //                 <!-- Subtotal -->
        //                 <div class="w-18 text-right">
        //                     <span class="text-xs text-gray-400 block font-normal">Subtotal</span>
        //                     <span class="text-sm font-semibold text-gray-800 subtotal-display" data-index="${index}">
        //                         ${itemTotal.toFixed(2)}
        //                     </span>
        //                 </div>

        //                 <!-- Botón Eliminar -->
        //                 <button type="button"
        //                     class="btn-remove-item flex h-8 w-8 items-center justify-center rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors"
        //                     data-id="${item.id}"
        //                     title="Eliminar producto">
        //                     <svg class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        //                         <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        //                     </svg>
        //                 </button>
        //             </div>
        //         </div>
        //     `;
        detailsContainer.appendChild(row);
    });

    updateTotalsDisplay(grandTotal);
}

// Escuchar cambios en los inputs de cantidad y precio dinámicamente
if (detailsContainer) {
    detailsContainer.addEventListener("input", (e) => {
        const input = e.target;
        const index = input.dataset.index;

        if (index === undefined) return;

        const itemIndex = Number.parseInt(index, 10);
        const item = purchaseItems[itemIndex];

        if (!item) return;

        // Actualizar objeto en memoria
        if (input.classList.contains("input-quantity")) {
            item.quantity = Number.parseFloat(input.value) || 0;
        } else if (input.classList.contains("input-price")) {
            item.price = Number.parseFloat(input.value) || 0;
        }

        // Actualizar subtotal en la interfaz en tiempo real
        const newSubtotal = item.quantity * item.price;
        const row = input.closest(".item-row");
        if (row) {
            const subtotalSpan = row.querySelector(".subtotal-display");
            if (subtotalSpan) {
                subtotalSpan.textContent = newSubtotal.toFixed(2);
            }
        }

        // Recalcular Total General
        let grandTotal = purchaseItems.reduce(
            (acc, p) => acc + p.quantity * p.price,
            0,
        );
        updateTotalsDisplay(grandTotal);
    });
}

// Función auxiliar para actualizar los inputs del total general
function updateTotalsDisplay(grandTotal) {
    if (totalDisplay) totalDisplay.textContent = grandTotal.toFixed(2);
    if (totalInput) totalInput.value = grandTotal.toFixed(2);
}

// ==========================================
// 4. ANULACIÓN DE COMPRAS (HISTORIAL)
// ==========================================
document.addEventListener("click", async (e) => {
    const btnCancel = e.target?.closest(".btn-cancel-purchase");
    if (!btnCancel) return;

    const purchaseId = btnCancel.dataset.id;

    const result = await Swal.fire({
        title: "¿Estás seguro de anular esta compra?",
        text: "Esta acción cambiará el estado a anulada y revertirá el stock ingresado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Sí, anular compra",
        cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
        try {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");

            const response = await fetch(`/admin/purchases/${purchaseId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json", // OBLIGATORIO para JSON
                    "X-Requested-With": "XMLHttpRequest", // OBLIGATORIO para Laravel AJAX
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    status: "canceled",
                }),
            });

            // Validar si la respuesta es un JSON antes de parsear
            const contentType = response.headers.get("content-type");
            let data = {};

            if (contentType && contentType.includes("application/json")) {
                data = await response.json();
            } else {
                throw new Error(
                    `El servidor respondió con estado ${response.status} sin formato JSON.`,
                );
            }

            if (response.ok && data.success) {
                if (typeof window.showToast === "function") {
                    window.showToast("success", data.message);
                }
                setTimeout(() => window.location.reload(), 1000);
            } else {
                throw new Error(
                    data.message || "Error al procesar la anulación.",
                );
            }
        } catch (error) {
            if (typeof window.showToast === "function") {
                window.showToast("error", error.message);
            }
        }
    }
});

// ==========================================
// 5. COMPONENTES GLOBALES (TOAST & MODAL DE DETALLE)
// ==========================================

// Configuración Global SweetAlert Toast
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

window.showToast = (icon, title) => {
    Toast.fire({ icon, title });
};

// Lógica para el Modal de Detalle de Compra
const modal = document.getElementById("purchase-detail-modal");
const closeModalBtn = document.getElementById("close-modal-btn");

if (modal) {
    // Abrir modal y consultar API
    document.addEventListener("click", async (e) => {
        const btn = e.target?.closest(".btn-show-purchase");
        if (!btn) return;

        const purchaseId = btn.dataset.id;

        try {
            const response = await fetch(`/admin/purchases/${purchaseId}`);
            if (!response.ok) throw new Error("Error al obtener el detalle");

            const purchase = await response.json();

            // Llenar información general del modal
            document.getElementById("modal-purchase-id").textContent =
                purchase.id;
            document.getElementById("modal-purchase-date").textContent =
                new Date(purchase.purchase_date).toLocaleDateString();
            document.getElementById("modal-purchase-total").textContent =
                Number.parseFloat(purchase.total).toFixed(2);

            // Renderizar la tabla de productos dentro del modal
            const itemsBody = document.getElementById("modal-items-body");
            itemsBody.innerHTML = "";

            purchase.products.forEach((product) => {
                const quantity = product.pivot.quantity;
                const price = Number.parseFloat(product.pivot.price);
                const subtotal = (quantity * price).toFixed(2);

                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-700">${product.codigo}</td>
                        <td class="px-4 py-3">${product.description}</td>
                        <td class="px-4 py-3 text-center">${quantity}</td>
                        <td class="px-4 py-3 text-right">${price.toFixed(2)} Bs.</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">${subtotal} Bs.</td>
                    </tr>
                `;
                itemsBody.insertAdjacentHTML("beforeend", row);
            });

            modal.classList.remove("hidden");
        } catch (error) {
            console.error(error);
            if (typeof window.showToast === "function") {
                window.showToast(
                    "error",
                    "No se pudo cargar el detalle de la compra.",
                );
            }
        }
    });

    // Cerrar modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () =>
            modal.classList.add("hidden"),
        );
    }

    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.classList.add("hidden");
    });
}
