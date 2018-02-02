<template>
    <page-load id="imbuements">
        <pre>
            Listar todos os imbuements registrados.
            Para cada tier do imbuement deverá ter a opção de selecionar gold token.

            A listagem de valores deverá mostrar o valor individual de cada imbuement
            e no final exibir o valor total, assim deixando mais claro o quanto que
            custa cada imbuement, e quais são os valores somados.
        </pre>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                imbuements: []
            }
        },

        mounted () {
            services.fecthImbuements()
                .then(response => {
                    this.imbuements = { ...response.data, basic: 0, intricate: 0, powerful: 0 }
                })
        }
    }
</script>