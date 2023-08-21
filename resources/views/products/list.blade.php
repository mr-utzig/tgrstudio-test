<x-layout>
    <div class="container mt-5">
        <h1>Gerenciamento de produtos</h1>

        <!-- Insert New Product Button -->
        <div class="row g-2 align-items-center my-3 d-flex justify-content-between">
            <div class="col-auto">
                <button class="btn btn-primary col-auto" data-bs-toggle="modal" data-bs-target="#insert-modal">
                    <i class="bi bi-plus-circle-fill"></i> Inserir novo produto
                </button>
            </div>
            <div class="col-auto">
                <form action="{{ route('products.filter') }}" class="row g-2 mb-0" method="GET">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="name" placeholder="Busque pelo nome do produto">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="{{ route('products.list') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-repeat"></i> Atualizar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div id="deleteAlertPlaceholder"></div>
        <!-- List of Products -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="product-list">
                @foreach ($products as $product)
                <tr>
                    <td class="align-middle">{{ $product->name }}</td>
                    <td class="align-middle">{{ $product->description }}</td>
                    <td class="align-middle">{{ $product->price }}</td>
                    <td class="align-middle">{{ $product->quantity }}</td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit-modal" data-bs-id="{{ $product->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger" onclick="deleteProduct('{{ $product->id }}')">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                        <!-- <button class="btn btn-danger deleteprod" id="deleteprod_{{ $product->id }}" data-id="{{ $product->id }}">
                            <i class="bi bi-trash3-fill"></i>
                        </button> -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <!-- Modal for Inserting New Product -->
        <div class="modal fade" id="insert-modal" tabindex="-1" aria-labelledby="insert-modal-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="insert-modal-label">Inserir novo produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="insertAlertPlaceholder"></div>
                        <form id="insertform">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price">
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="quantity" name="quantity">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="submitinsert">Inserir</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Editing Product -->
        <div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="edit-modal-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="insert-modal-label">Editar produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="editAlertPlaceholder"></div>
                        <form id="editform">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-description" class="form-label">Descrição</label>
                                <textarea class="form-control" id="edit-description" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit-price" class="form-label">Preço</label>
                                <input type="number" step="0.01" class="form-control" id="edit-price" name="price">
                            </div>
                            <div class="mb-3">
                                <label for="edit-quantity" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="edit-quantity" name="quantity">
                            </div>
                            <input type="hidden" id="edit-id" name="id">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="submitedit">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const editmodal = document.getElementById('edit-modal')
        if (editmodal) {
            editmodal.addEventListener('show.bs.modal', async event => {
                const button = event.relatedTarget
                const id = button.getAttribute('data-bs-id')

                const response = await (await fetch(`/product/${id}`)).json();

                // Update the modal's content.
                editmodal.querySelector('input#edit-name').value = response.product.name;
                editmodal.querySelector('textarea#edit-description').value = response.product.description;
                editmodal.querySelector('input#edit-price').value = response.product.price;
                editmodal.querySelector('input#edit-quantity').value = response.product.quantity;
                editmodal.querySelector('input#edit-id').value = response.product.id;
            });
        }

        submitinsert.addEventListener('click', async event => {
            event.preventDefault();

            const payload = new FormData(insertform);
            await fetch(`/product`, {
                    method: "POST",
                    body: payload
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            const errorMessages = [];
                            for (const key in err.message) {
                                if (err.message.hasOwnProperty(key)) {
                                    const messages = err.message[key];
                                    errorMessages.push(...messages);
                                }
                            }

                            throw new Error(errorMessages.join('<br>'));
                        });
                    }

                    return response.json();
                })
                .then(response => {
                    appendAlert(response.message, 'success', 'insertAlertPlaceholder');
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(err => appendAlert(err.message, 'warning', 'insertAlertPlaceholder'));
        });

        submitedit.addEventListener('click', async event => {
            event.preventDefault();

            const payload = new FormData(editform);
            await fetch(`/product/${editform.querySelector('input#edit-id').value}`, {
                    method: "POST",
                    body: payload
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            const errorMessages = [];
                            for (const key in err.message) {
                                if (err.message.hasOwnProperty(key)) {
                                    const messages = err.message[key];
                                    errorMessages.push(...messages);
                                }
                            }

                            throw new Error(errorMessages.join('<br>'));
                        });
                    }

                    return response.json();
                })
                .then(response => {
                    appendAlert(response.message, 'success', 'editAlertPlaceholder');
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(err => appendAlert(err.message, 'warning', 'editAlertPlaceholder'));
        });

        function deleteProduct(productId) {
            if (confirm('Tem certeza que deseja remover esse produto?')) {
                fetch(`/product/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                const errorMessages = [];
                                for (const key in err.message) {
                                    if (err.message.hasOwnProperty(key)) {
                                        const messages = err.message[key];
                                        errorMessages.push(...messages);
                                    }
                                }

                                throw new Error(errorMessages.join('<br>'));
                            });
                        }

                        return response.json();
                    })
                    .then(response => {
                        appendAlert(response.message, 'success', 'deleteAlertPlaceholder');
                        setTimeout(() => location.reload(), 1500);
                    }).catch(err => appendAlert(err.message, 'warning', 'deleteAlertPlaceholder'));
            }
        }

        const appendAlert = (message, type, element) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = `<div class="alert alert-${type} alert-dismissible" role="alert"><div>${message}</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;

            document.getElementById(element).append(wrapper)
        }
    </script>
</x-layout>