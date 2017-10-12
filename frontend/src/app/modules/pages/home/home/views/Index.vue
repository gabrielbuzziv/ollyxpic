<template>
    <page-load>
        <div class="alert alert-warning">
            <p>We are currently updating to the newest update so items from summer update might not show up yet.</p>
        </div>

        <panel title="News" class="margin-bottom-50">
            Ollyxpic is up and running again!
        </panel>

        <div class="row">
            <div class="col-md-4">
                <card title="Supplies" subtitle="Waste">
                    <img :src="image_path('item', 3147)" slot="icon">
                    <router-link :to="{ name: 'calculators.waste' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Imbuements" subtitle="Waste/Time" dark>
                    <img :src="image_path('item', 2655)" slot="icon">
                    <router-link :to="{ name: 'calculators.imbuements' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Hunts" subtitle="Waste/Profit">
                    <img :src="image_path('item', 296)" slot="icon">
                    <router-link :to="{ name: 'calculators.hunt' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Loot" subtitle="Count">
                    <img :src="image_path('item', 93)" slot="icon">
                    <router-link :to="{ name: 'calculators.loot.count' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Loot" subtitle="Acumulator" dark>
                    <img :src="image_path('item', 1613)" slot="icon">
                    <router-link :to="{ name: 'calculators.loot.acumulator' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Blessings" subtitle="Penalty">
                    <img :src="image_path('item', 1922)" slot="icon">
                    <router-link :to="{ name: 'calculators.blessing' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Speed" subtitle="Boost">
                    <img :src="image_path('item', 1)" slot="icon">
                    <router-link :to="{ name: 'calculators.speedboost' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>

            <div class="col-md-4">
                <card title="Warzone" subtitle="MVP's" dark>
                    <img :src="image_path('item', 862)" slot="icon">
                    <router-link :to="{ name: 'calculators.mvp' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>
			
            <div class="col-md-4">
                <card title="Damage" subtitle="Protection">
                    <img :src="image_path('item', 208)" slot="icon">
                    <router-link :to="{ name: 'calculators.damage.protection' }" slot="anchor">
                        <i class="mdi mdi-chevron-right"></i>
                    </router-link>
                </card>
            </div>
        </div>
    </page-load>
</template>