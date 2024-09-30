export const TableHelpers = {
    data() {
        return {
            tableColumns : [
                {
                    title: this.$t('EmployeeProfile'),
                    type: 'object',
                    key: 'media-object',
                    // mediaTitleKey: 'full_name',
                    // mediaSubtitleKey: 'department',
                    default: AppFunction.getAppUrl('images/avatar-demo.jpg'),
                    modifier: (value, row) => {
                        return value;
                    },
                    isVisible: true
                },
                {
                    title: this.$t('EmployeeID'),
                    type: 'text',
                    key: 'employee_id',
            
                },

                {
                    title: this.$t('DateTime'),
                    type: 'text',
                    key: 'datetime',
                },
                {
                    "title": this.$t('checkIn/Out'),
                    type: 'custom-html',
                    key: 'checkin',
                    isVisible: true,
                    modifier: (value) => {
                                let ClassName = `danger`;
                                let status = ``
                                if (value == 0) {
                                    ClassName = `success`;
                                    status=`IN`;
                                }

                                else if (value == 1) {
                                    ClassName = `warning`;
                                    status =`OUT`;
                                }
                                else{
                                   status= `undefined`;
                                }
                           
                                return `<span class="badge badge-sm badge-pill badge-${ClassName}">${status}</span>`;
                            }
                        
                },
            ],
            actionObj : {
                title: this.$t('action'),
                type: 'action',
            }
        }
    },
}