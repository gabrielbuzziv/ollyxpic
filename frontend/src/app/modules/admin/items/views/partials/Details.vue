<template>
    <tr class="details" v-if="show">
        <td colspan="4">
            <div class="row">
                <div class="col-md-12 margin-top-10">
                    <h4>Properties</h4>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="data">
                                <b>Weight</b>
                                <span>{{ item.capacity }} oz</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="data">
                                <b>Stackable</b>
                                <span>
                                        <i class="mdi mdi-check-circle" v-if="item.stackable"></i>
                                        <i class="mdi mdi-close-circle" v-else></i>
                                    </span>
                            </div>
                        </div>

                        <div class="col-md-4" v-for="property in item.properties">
                            <div class="data">
                                <b>{{ property.property }}</b>
                                <span>{{ getPropertyValue(property) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 margin-top-10" v-if="item.sells.length">
                    <h4>Sell for</h4>

                    <div class="row">
                        <div class="col-md-3" v-for="sells in item.sells">
                            <div class="data">
                                <b>{{ sells.npc.name }}</b>
                                <span>{{ sells.value }} gp</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 margin-top-10" v-if="item.buys.length">
                    <h4>Buy for</h4>

                    <div class="row">
                        <div class="col-md-3" v-for="buys in item.buys">
                            <div class="data">
                                <b>{{ buys.npc.name }}</b>
                                <span>{{ buys.value }} gp</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['item', 'show'],

        methods: {
            getPropertyValue (property) {
                if (property.property == 'Voc') {
                    return property.value.replace('k', 'Knight')
                        .replace('s', 'Sorcerer')
                        .replace('d', 'Druid')
                        .replace('p', 'Paladin')
                        .replace('+', ' and ')
                }

                if (property.property == 'Attrib') {
                    return property.value.replace('sword', 'Sword')
                        .replace('axe', 'Axe')
                        .replace('club', 'Club')
                        .replace('distance', 'Distance')
                        .replace('magic', 'ML')
                        .replace('faster', 'Faster')
                        .replace('regen', 'Regeneration')
                }

                return property.value
            },
        }
    }
</script>