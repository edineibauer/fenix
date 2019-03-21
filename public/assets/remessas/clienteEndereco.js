$(".list-value-hidden").off("change").on("change", function() {
    let $this = $(this);
    let entity = $this.attr("data-entity");
    let valor = $this.val();

    if(valor !== "" && !isNaN(valor)) {
        db.exeRead(entity, parseInt(valor)).then(d => {
            if(!isEmpty(d.enderecos)) {
                let endereco = d.enderecos[0];
                let form = forms[$this.attr("rel")];
                let parent = $this.attr("data-parent").replace(form.entity + ".", "").replace(form.entity, "");
                let data = parent !== "" ? fetchFromObject(form.data, parent) : form.data;
                data.endereco = {
                    numero: endereco.numero,
                    logradouro: endereco.logradouro,
                    cep: endereco.cep,
                    bairro: endereco.bairro || "",
                    cidade: endereco.cidade || "",
                    estado: endereco.estado || "",
                    complemento: endereco.complemento || "",
                };

                let $endereco = $this.parent().parent().parent().find("div[data-column='endereco']");
                $endereco.find("input[data-column='numero']").val(data.endereco.numero);
                $endereco.find("input[data-column='logradouro']").val(data.endereco.logradouro);
                $endereco.find("input[data-column='bairro']").val(data.endereco.bairro);
                $endereco.find("input[data-column='cidade']").val(data.endereco.cidade);
                $endereco.find("input[data-column='estado']").val(data.endereco.estado);
                $endereco.find("input[data-column='cep']").val(data.endereco.cep);
                $endereco.find("input[data-column='complemento']").val(data.endereco.complemento);
            }
        });
    }
});