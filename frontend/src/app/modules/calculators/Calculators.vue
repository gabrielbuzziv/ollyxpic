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

            <card :to="{ name: 'calculators.loot' }"
                  :columns="4"
                  :image="item_path('steel-boots')"
                  title="Loot Count">
                Need to count the value of your loots? problems solved.
            </card>
        </div>
    </page-load>
</template>