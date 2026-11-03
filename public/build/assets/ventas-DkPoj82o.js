var e=[],t=document.getElementById(`sales-details-container`),n=document.getElementById(`total-display`),r=document.getElementById(`total-input`);document.addEventListener(`click`,e=>{let t=e.target?.closest(`.btn-add-product`);if(t&&!t.disabled){let e=t.dataset.id,n=t.dataset.code,r=t.dataset.description,a=Number.parseInt(t.dataset.stock,10)||0,o=Number.parseFloat(t.dataset.price)||0;t.disabled=!0,t.classList.add(`opacity-50`,`cursor-not-allowed`),t.textContent=`Agregado`,i(e,n,r,a,o)}});function i(t,n,r,i,o){if(i<=0){typeof window.showToast==`function`&&window.showToast(`error`,`Este producto no tiene stock disponible.`);return}let s=e.find(e=>e.id===t);if(s){if(s.quantity+1>i){typeof window.showToast==`function`&&window.showToast(`error`,`Solo hay ${i} unidades disponibles.`);return}s.quantity+=1}else e.push({id:t,code:n,description:r,stock:i,quantity:1,price:o});a()}t&&t.addEventListener(`click`,t=>{let n=t.target?.closest(`.btn-remove-item`);if(n){let t=n.dataset.id,r=e.findIndex(e=>e.id===t);r!==-1&&e.splice(r,1);let i=document.querySelector(`.btn-add-product[data-id="${t}"]`);i&&(i.disabled=!1,i.classList.remove(`opacity-50`,`cursor-not-allowed`),i.textContent=`Agregar`),a()}});function a(){if(!t)return;t.innerHTML=``;let n=0;e.forEach((e,r)=>{let i=e.quantity*e.price;n+=i;let a=document.createElement(`div`);a.classList.add(`mb-2`),a.innerHTML=`
                <div class="item-row grid grid-cols-4 items-end gap-3 py-3">

                    <!-- Info del Producto -->
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-zinc-300 truncate">${e.description}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-mono">
                            <span class="text-emerald-600 font-sans font-medium">(Stock: ${e.stock})</span>
                        </div>
                        <input type="hidden" name="items[${r}][product_id]" value="${e.id}">
                    </div>

                    <!-- Cantidad a Vender -->
                    <div class="">
                        <label class="sr-only">Cantidad</label>
                        <input type="number"
                            name="items[${r}][quantity]"
                            class="input-quantity block w-full rounded-md border border-zinc-200 dark:bg-white/10 bg-gray-50 py-1.5 dark:text-zinc-300 text-center text-sm font-medium text-zinc-700 dark:text-zinc-300"
                            data-index="${r}"
                            value="${e.quantity}"
                            min="1"
                            max="${e.stock}"
                            placeholder="Cant.">
                    </div>

                    <!-- Precio de Venta Unitario -->
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                            <span class="text-xs font-medium text-gray-600 dark:text-zinc-300">Bs.</span>
                        </div>
                        <input type="number"
                            step="0.01"
                            name="items[${r}][price]"
                            class="input-price block w-full rounded-md border border-gray-300 bg-gray-50 dark:bg-white/10 py-1.5 pl-8 pr-2 text-right text-sm font-medium text-zinc-700 dark:text-zinc-300"
                            data-index="${r}"
                            value="${e.price}"
                            readonly />
                    </div>

                    <!-- Subtotal -->
                    <div class="flex gap-1 items-end">
                        <div>
                            <span class="text-xs text-gray-400 block font-normal">Subtotal</span>
                            <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 subtotal-display" data-index="${r}">
                                ${i.toFixed(2)}
                            </span>
                        </div>
                        <!-- Botón Eliminar -->
                        <button type="button"
                            class="btn-remove-item flex h-8 w-8 items-center justify-center rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors"
                            data-id="${e.id}"
                            title="Quitar producto">
                            <svg class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>


                </div>
            `,t.appendChild(a)}),o(n)}t&&t.addEventListener(`input`,t=>{let n=t.target,r=n.dataset.index;if(r===void 0)return;let i=e[Number.parseInt(r,10)];if(!i)return;if(n.classList.contains(`input-quantity`)){let e=Number.parseFloat(n.value)||0;e>i.stock&&(e=i.stock,n.value=i.stock,typeof window.showToast==`function`&&window.showToast(`warning`,`Stock máximo (${i.stock} unidades)`)),i.quantity=e}else n.classList.contains(`input-price`)&&(i.price=Number.parseFloat(n.value)||0);let a=i.quantity*i.price,s=n.closest(`.grid`);if(s){let e=s.querySelector(`.subtotal-display`);e&&(e.textContent=a.toFixed(2))}o(e.reduce((e,t)=>e+t.quantity*t.price,0))});function o(e){n&&(n.textContent=e.toFixed(2)),r&&(r.value=e.toFixed(2))}document.addEventListener(`click`,async e=>{let t=e.target?.closest(`.btn-cancel-purchase`);if(!t)return;let n=t.dataset.id;if((await Swal.fire({title:`¿Estás seguro de anular esta compra?`,text:`Esta acción cambiará el estado a anulada y revertirá el stock ingresado.`,icon:`warning`,showCancelButton:!0,confirmButtonColor:`#ef4444`,cancelButtonColor:`#6b7280`,confirmButtonText:`Sí, anular compra`,cancelButtonText:`Cancelar`})).isConfirmed)try{let e=document.querySelector(`meta[name="csrf-token"]`)?.getAttribute(`content`),t=await fetch(`/admin/purchases/${n}`,{method:`PUT`,headers:{"Content-Type":`application/json`,Accept:`application/json`,"X-Requested-With":`XMLHttpRequest`,"X-CSRF-TOKEN":e},body:JSON.stringify({status:`canceled`})}),r=t.headers.get(`content-type`),i={};if(r&&r.includes(`application/json`))i=await t.json();else throw Error(`El servidor respondió con estado ${t.status} sin formato JSON.`);if(t.ok&&i.success)typeof window.showToast==`function`&&window.showToast(`success`,i.message),setTimeout(()=>window.location.reload(),1e3);else throw Error(i.message||`Error al procesar la anulación.`)}catch(e){typeof window.showToast==`function`&&window.showToast(`error`,e.message)}});var s=Swal.mixin({toast:!0,position:`top-end`,showConfirmButton:!1,timer:3e3,timerProgressBar:!0,didOpen:e=>{e.addEventListener(`mouseenter`,Swal.stopTimer),e.addEventListener(`mouseleave`,Swal.resumeTimer)}});window.showToast=(e,t)=>{s.fire({icon:e,title:t})};var c=document.getElementById(`purchase-detail-modal`),l=document.getElementById(`close-modal-btn`);c&&(document.addEventListener(`click`,async e=>{let t=e.target?.closest(`.btn-show-purchase`);if(!t)return;let n=t.dataset.id;try{let e=await fetch(`/admin/purchases/${n}`);if(!e.ok)throw Error(`Error al obtener el detalle`);let t=await e.json();document.getElementById(`modal-purchase-id`).textContent=t.id,document.getElementById(`modal-purchase-date`).textContent=new Date(t.purchase_date).toLocaleDateString(),document.getElementById(`modal-purchase-total`).textContent=Number.parseFloat(t.total).toFixed(2);let r=document.getElementById(`modal-items-body`);r.innerHTML=``,t.products.forEach(e=>{let t=e.pivot.quantity,n=Number.parseFloat(e.pivot.price),i=(t*n).toFixed(2),a=`
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-700">${e.codigo}</td>
                        <td class="px-4 py-3">${e.description}</td>
                        <td class="px-4 py-3 text-center">${t}</td>
                        <td class="px-4 py-3 text-right">${n.toFixed(2)} Bs.</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">${i} Bs.</td>
                    </tr>
                `;r.insertAdjacentHTML(`beforeend`,a)}),c.classList.remove(`hidden`)}catch(e){console.error(e),typeof window.showToast==`function`&&window.showToast(`error`,`No se pudo cargar el detalle de la compra.`)}}),l&&l.addEventListener(`click`,()=>c.classList.add(`hidden`)),c.addEventListener(`click`,e=>{e.target===c&&c.classList.add(`hidden`)}));