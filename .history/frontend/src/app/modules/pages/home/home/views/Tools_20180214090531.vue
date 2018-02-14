<template>
    <div class="row tools">
        <h4>Tools</h4>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.imbuements' }" slot="anchor">
                <card title="Imbuements" subtitle="Waste/Time">
                    <img :src="image_path_by_name('item', 'silencer claws')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.loot.count' }" slot="anchor">
                <card title="Loot" subtitle="Count">
                    <img :src="image_path_by_name('item', 'steel boots')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.blessing' }" slot="anchor">
                <card title="Blessings" subtitle="Penalty">
                    <img :src="image_path_by_name('item', 'spiritual charm')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.speedboost' }" slot="anchor">
                <card title="Speed" subtitle="Boost">
                    <img :src="image_path_by_name('item', 'boots of haste')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.damage.healing' }" slot="anchor">
                <card title="Damage Calc" subtitle="Damage & Healing" dark>
                    <img :src="image_path_by_name('item', 'sudden death rune')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.expshare' }" slot="anchor">
                <card title="Exp" subtitle="Share">
                    <img :src="image_path_by_name('item', 'purple tome')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.capcount' }" slot="anchor">
                <card title="Cap" subtitle="Count">
                    <img :src="image_path_by_name('item', 'blossom bag')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.damage.protection' }" slot="anchor">
                <card title="Damage" subtitle="Protection">
                    <img :src="image_path_by_name('item', 'great shield')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.currencies' }" slot="anchor">
                <card title="Tibia Currency" subtitle="Stock Exchange">
                    <img :src="image_path_by_name('item', 'tibia coins')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.blacklist' }" slot="anchor">
                <card title="Blacklist" subtitle="Quick Looting">
                    <img :src="image_path_by_name('item', 'Book (Black)')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.timer' }" slot="anchor">
                <card title="Task & Boss" subtitle="Timer">
                    <img :src="image_path_by_name('item', 'Ancient Watch')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.training' }" slot="anchor">
                <card title="Magic Level" subtitle="Training">
                    <img :src="image_path_by_name('item', 'Spellbook of Ancient Arcana')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>

        <div class="col-md-3">
            <router-link :to="{ name: 'tools.hardcore' }" slot="anchor">
                <card title="Hardcore PvP" subtitle="Experience">
                    <img :src="image_path_by_name('item', 'sudden death rune')" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
            </router-link>
        </div>
    </div>
</template>