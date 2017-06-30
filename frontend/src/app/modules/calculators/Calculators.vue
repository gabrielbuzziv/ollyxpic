<template>
    <page-load>
        <page-title>
            Calculators
        </page-title>

        <div class="row">
            <card :to="{ name: 'calculators.waste' }"
                  :columns="4"
                  :image="item_path('ultimate-mana-potion')"
                  title="Supplies Waste">
                Calculate all your waste during a hunt with the exactly
                amount of money wasted.
            </card>

            <card :to="{ name: 'calculators.teamhunt' }"
                  :columns="4"
                  :image="item_path('crystal-coin')"
                  title="Team Hunt Profit">
                Calculate the profit and the amount to pay for each one in the Team Hunt.
            </card>

            <card :to="{ name: 'calculators.waste' }"
                  :columns="4"
                  :image="item_path('boots-of-haste')"
                  title="Speed Limits">
                Calculate the required level to reach the Boosted Speed in each tile.
            </card>
        </div>
    </page-load>
</template>