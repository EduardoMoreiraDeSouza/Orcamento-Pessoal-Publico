    <a class="btn btn-dark btn-sm" style="font-size: 1.7vh;" type="button" data-bs-toggle="collapse"
            data-bs-target="#novoBancoCorretora" aria-expanded="false"
            aria-controls="collapseWidthExample">
        Novo Banco/Corretora
    </a>

    <div class="mt-1">
        <div class="collapse collapse-horizontal" id="novoBancoCorretora">
            <div class="card card-body border border-dark bg-secondary"
                 style="">
                <form action="../backEnd/InteracaoFront/novoBancoCorretora.php"
                      method="POST"
                      class="form hstack gap-3">

                    <input type="text" class="container input-group-text" name="bancoCorretora"
                           placeholder="Nome:"
                           required>
                    <input type="text" class="container input-group-text" name="saldo"
                           placeholder="Saldo:" step="0.01">

                    <input type="submit" class="container btn btn-dark" value="Criar">

                </form>
            </div>
        </div>
    </div>
