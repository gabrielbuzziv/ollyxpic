import services from '../services'

export default {
    'waste/FETCH_SUPPLIES' (context) {
        // Potions
        services.fetchPotions()
            .then(response => {
                const potions = response.data.map(potion => {
                    return { value: potion.id, image: potion.image_url, label: potion.title }
                })
                context.commit('waste/POTIONS', potions)
            })

        // Ammunitions
        services.fetchAmmunitions()
            .then(response => {
                const ammunitions = response.data.map(ammunition => {
                    return { value: ammunition.id, image: ammunition.image_url, label: ammunition.name }
                })
                context.commit('waste/AMMUNITIONS', ammunitions)
            })

        // Runes
        services.fetchRunes()
            .then(response => {
                const runes = response.data.map(rune => {
                    return { value: rune.id, image: rune.image_url, label: rune.title }
                })
                context.commit('waste/RUNES', runes)
            })

        // Amulets
        services.fetchAmulets()
            .then(response => {
                const amulets = response.data.map(amulet => {
                    return { value: amulet.id, image: amulet.image_url, label: amulet.title }
                })
                context.commit('waste/AMULETS', amulets)
            })

        // Rings
        services.fetchRings()
            .then(response => {
                const rings = response.data.map(ring => {
                    return { value: ring.id, image: ring.image_url, label: ring.title }
                })
                context.commit('waste/RINGS', rings)
            })
    }
}