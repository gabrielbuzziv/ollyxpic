<template>
    <page-load>
        <div class="alert alert-warning">
            <p>We are currently updating to the newest update so items from summer update might not show up yet.</p>
        </div>

        <panel title="News" class="margin-bottom-50">
            Character copy date for test server is set! see news section for more info!
        </panel>

        <div class="row">
            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.waste' }" slot="anchor">
                <card title="Supplies" subtitle="Waste">
                    <img :src="image_path('item', 3147)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.imbuements' }" slot="anchor">
                <card title="Imbuements" subtitle="Waste/Time" dark>
                    <img :src="image_path('item', 2655)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.hunt' }" slot="anchor">
                <card title="Hunts" subtitle="Waste/Profit">
                    <img :src="image_path('item', 296)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.loot.count' }" slot="anchor">
                <card title="Loot" subtitle="Count">
                    <img :src="image_path('item', 93)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.loot.acumulator' }" slot="anchor">
                <card title="Loot" subtitle="Acumulator" dark>
                    <img :src="image_path('item', 1613)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.blessing' }" slot="anchor">
                <card title="Blessings" subtitle="Penalty">
                    <img :src="image_path('item', 1922)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.speedboost' }" slot="anchor">
                <card title="Speed" subtitle="Boost">
                    <img :src="image_path('item', 1)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>

            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.spellcaster' }" slot="anchor">
                <card title="Spellcaster" subtitle="Damage & Healing" dark>
                    <img :src="image_path('item', 117)" slot="icon">
                    <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>			
			
            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.mvp' }" slot="anchor">
                <card title="Warzone" subtitle="MVP's">
                    <img :src="image_path('item', 862)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>
			
            <div class="col-md-4">
				<router-link :to="{ name: 'calculators.damage.protection' }" slot="anchor">
                <card title="Damage" subtitle="Protection">
                    <img :src="image_path('item', 208)" slot="icon">
                        <i class="mdi mdi-chevron-right"></i>
                </card>
				</router-link>
            </div>
        </div>
    </page-load>
</template>