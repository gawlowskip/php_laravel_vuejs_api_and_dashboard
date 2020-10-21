export const formMixin = {
    data() {
        return {
            userForm: {
                activeOptions: [],
                stripePlanOptions: [],
                agreementTypeOptions: [],
            },
            adForm: {
                activeOptions: [],
                areaOptions: []
            }
        }
    },
    created() {
        this.userForm.activeOptions = [
            {
                'name': this.trans.get('active'),
                'value': '1'
            },
            {
                'name': this.trans.get('inactive'),
                'value': '0'
            }
        ]

        this.adForm.activeOptions = this.userForm.activeOptions

        if (this.stripePlans) {
            this.stripePlans.forEach(plan => {
                this.userForm.stripePlanOptions.push({
                    'name': plan.metadata.name,
                    'value': plan.id
                })
            })
        }

        this.userForm.agreementTypeOptions = [
            {
                'name': this.trans.get('regular'),
                'value': 'regular'
            },
            {
                'name': this.trans.get('trial'),
                'value': 'trial'
            }
        ]

        if (this.areas) {
            this.areas.forEach(area => {
                this.adForm.areaOptions.push({
                    'name': area.name,
                    'value': area.id
                })
            })
        }
    }
}