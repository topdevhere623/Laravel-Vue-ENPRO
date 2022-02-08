import { mapGetters } from 'vuex';

export default {
    props: {},

    watch: {},

    computed: {
        ...mapGetters(['findById', 'allState', 'id']),
    },

    methods: {
        generateId() {
            function chr4() {
                return Math.random().toString(16).slice(-4);
            }

            return chr4() + chr4() +
                '-' + chr4() +
                '-' + chr4() +
                '-' + chr4() +
                '-' + chr4() + chr4() + chr4();
        },
        stateToData(elements) {
            const data = [];

            //Мета элементы
            //Substation

            const substationId = this.id || this.generateId();

            if (!this.id) {
                this.$store.dispatch('createSubstationID', substationId);
            }

            const substationData = {
                class: 'Substation',
                mRID: substationId,
            };

            data.push(substationData);

            const transformers = this.elementsList.filter(el => el.type === 'transformer');

            // VoltageLevel
            const voltageLevels = [];
            const tires = this.elementsList.filter(el => el.type === 'top-tire' || el.type === 'bottom-tire' );

            tires.forEach(el => {
                if (el.voltageLevel.value) {
                    voltageLevels.push(el.voltageLevel);
                }
            });

            this.elementsList.filter(el => el.type === 'custom-cell').forEach(el => {
                if (el.currentTireID) {} else {
                    voltageLevels.push(el.voltageLevel);
                }
            });

            const filteredVoltageLevels = voltageLevels.reduce((acc, current) => {
                if (!acc.includes(current.value)) {
                    acc.push(current.value);
                }
                return acc;
            }, []).map(val => voltageLevels.find(el => el.value === val));

            filteredVoltageLevels.forEach(voltage => {
                const voltageData = {
                    class: 'VoltageLevel',
                    name: voltage.caption,
                    voltage: voltage.value,
                    substation: substationData.mRID,
                    mRID: voltage.id,
                };

                data.push(voltageData);
            });

            elements.forEach(el => {
                // Simple data
                const elementData = {
                    class: el.class || null,
                    mRID: el.id,
                    name: el.caption || null,
                };

                // Tire terminals
                if (el.connection || el.beforeTire || el.afterTire) {
                    elementData.terminals = [];

                    if (el.beforeTire) {
                        const cell = this.findById(el.beforeTire);
                        const splittingID = el.beforeTire.split('-');
                        elementData.terminals.push({
                            mRID: cell.id + '-' + splittingID[1],
                            sequenceNumber: cell.index,
                        });
                    }

                    el.connection.forEach(elementID => {
                        const splittingID = elementID.split('-');
                        elementData.terminals.push({
                            mRID: elementID + '-' + splittingID[0],
                            sequenceNumber: this.findById(elementID).index,
                        });
                    });

                    if (el.afterTire) {
                        const cell = this.findById(el.afterTire);
                        const splittingID = el.afterTire.split('-');
                        elementData.terminals.push({
                            mRID: cell.id + '-' + splittingID[0],
                            sequenceNumber: cell.index2,
                        });
                    }

                }

                //Models
                if (el.mark) {
                    !elementData.asset ? elementData.asset = {} : false;
                    elementData.asset.assetInfo = el.mark.value;
                }

                //Transformer
                if (el.type === 'transformer') {
                    elementData.EquipmentContainer = substationId;
                }

                if (el.class === 'BusbarSection') {
                    if (el.type === 'top-tire') {
                        elementData.EquipmentContainer = filteredVoltageLevels.find(el => el.orientation === 'income')?.id || substationId;
                    } else if (el.type === 'bottom-tire') {
                        elementData.EquipmentContainer = filteredVoltageLevels.find(el => el.orientation === 'outcome')?.id || substationId;
                    }
                }


                if (el.class === 'Bay') {
                    if (el.currentTireID) {
                        const currentTire = tires.find(t=>t.id===el.currentTireID);
                        elementData.EquipmentContainer = currentTire.voltageLevel.id;
                    } else {
                        elementData.EquipmentContainer = el.voltageLevel.id;
                    }
                }

                data.push(elementData);

                // Терминалы трансформатора
                if (el.type === 'transformer') {
                    const splittingID = el.id.split('-');

                    if (el.income?.voltage?.value) {
                        const incomeData = {
                            class: 'PowerTransformerEnd',
                            name: el.income?.voltage?.caption,
                            mRID: el.id + '-' + splittingID[0],
                            BaseVoltage: el.income?.voltage?.value,
                            PowerTransformer: el.id,
                            terminal: {
                                mRID: el.id + '-' + splittingID[0] + splittingID[1],
                                sequenceNumber: 1,
                            },
                        };
                        data.push(incomeData);
                    }


                    if (el.outcome?.voltage?.value) {
                        const outcomeData = {
                            class: 'PowerTransformerEnd',
                            name: el.outcome?.voltage?.caption,
                            mRID: el.id + '-' + splittingID[1],
                            BaseVoltage: el.outcome?.voltage?.value,
                            PowerTransformer: el.id,
                            terminal: {
                                mRID: el.id + '-' + splittingID[1] + splittingID[0],
                                sequenceNumber: 1,
                            },
                        };

                        data.push(outcomeData);
                    }
                }

                //Содержимое редактируемых ячеек
                if (el.type === 'custom-cell') {
                    if (el.templateData?.elements) {
                        // Добавление компонентов в пулл
                        el.templateData?.elements.forEach(component => {
                            if (component.type !== 'line') {
                                const componentData = {
                                    class: component.class || null,
                                    mRID: component.id,
                                    name: component.caption || null,
                                    EquipmentContainer: el.id,
                                    terminals: component.terminals || [],
                                };

                                if (component.hasOwnProperty('switchElement')) {
                                    componentData.normalOpen = !component.switchElement
                                }

                                data.push(componentData);
                            }
                        });

                        //Соединения терминалов
                        const splittingID = el.id.split('-');

                        const terminalsCollection = [];

                        //Соединение с шиной (afterTire терминал)
                        if (el.admittance === 'between-tire') {
                            terminalsCollection.push([`${el.id}-${splittingID[1]} BusbarSection terminal ${el.index2}`]);
                        }

                        if (el.admittance !== 'between-tire' && el.reverse) {
                            //Соединение с шиной (beforeTire терминал или connection терминал)
                            terminalsCollection.push([`${el.id}-${splittingID[0]} BusbarSection terminal ${el.index}`]);
                        }

                        if (el.admittance === 'bottom-tire') {
                            transformers.forEach(transformer => {
                                const splittingTransformerID = transformer.id.split('-');
                                const terminals = ['income', 'outcome'];
                                terminals.forEach(terminal => {
                                    if (transformer[terminal].id === el.id) {
                                        terminalsCollection.push([`${transformer.id + '-' + splittingTransformerID[terminal === 'income' ? 0 : 1]} PowerTransformerEnd ${el[terminal]?.voltage?.caption || ''} terminal 2`]);
                                    }
                                });
                            });
                        }

                        el.templateData?.elements.forEach(component => {
                            if (component.type !== 'line') {
                                const terminals = [];
                                component.terminals.forEach(terminal => {
                                    terminals.push(`${terminal.id} ${component.class} terminal ${terminal.number}`);
                                });
                                terminalsCollection.push(terminals);
                            }
                        });

                        if (el.admittance === 'top-tire') {
                            transformers.forEach(transformer => {
                                const splittingTransformerID = transformer.id.split('-');
                                const terminals = ['income', 'outcome'];
                                terminals.forEach(terminal => {
                                    if (transformer[terminal].id === el.id) {
                                        terminalsCollection.push([`${transformer.id + '-' + splittingTransformerID[terminal === 'income' ? 0 : 1]} PowerTransformerEnd ${el[terminal]?.voltage?.caption || ''} terminal 1`]);
                                    }
                                });
                            });
                        }

                        if (el.admittance === 'between-tire' || !el.reverse) {
                            //Соединение с шиной (beforeTire)
                            terminalsCollection.push([`${el.id}-${splittingID[0]} BusbarSection terminal ${el.index}`]);
                        }

                        const connections = [];
                        terminalsCollection.forEach((terminal, index)=> {
                            if (terminalsCollection.length > index + 1) {
                                const terminals = [];
                                const pointStart = terminal[terminal.length - 1];
                                let pointEnd = null;

                                terminalsCollection.forEach((tr, i) => {
                                    if (!pointEnd && i > index) {
                                        pointEnd = tr[0];
                                    }
                                });

                                terminals.push(pointStart)
                                terminals.push(pointEnd)

                                connections.push({class: 'ConnectivityNode', mRID: this.generateId() , terminals: terminals})
                            }
                        })
                        let newConnections = [];
                        let lockItem = null;
                        connections.forEach((el, index) => {
                            if (lockItem !== index ) {
                                if (connections[index + 1] && el.terminals[1] === connections[index + 1].terminals[0]) {
                                    el.terminals.push(connections[index + 1].terminals[1])
                                    newConnections.push(el);
                                    lockItem = index + 1;
                                } else {
                                    newConnections.push(el);
                                }
                            }
                        })

                        newConnections.forEach(el => {
                            data.push(el)
                        })
                    }
                }

            });

            return data;
        },
    },
};
