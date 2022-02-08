<template>
  <div>
    <div class="svg-wrap" ref="editorSVGWrap">
      <svg @mousemove="updateMousePosition"
           @mouseover="mousemove = true"
           @click="takePoint"
           @mouseleave="mousemove = false"
           ref="editorSVG"
           width="100%"
           class="editor-area"
           :style="{
                     cursor: cursor ? cursor : ''
                 }"
           :viewBox="'0 0 ' + SVGParams.width + ' ' + SVGParams.height"
           :enable-background="'new 0 0 ' + SVGParams.width + ' ' + SVGParams.height"
      >
        <filter id="dropshadow">
          <feDropShadow dx="0" dy="0" stdDeviation="5"
                        flood-color="cyan"/>
        </filter>
        <defs>
          <circle id="point-handle"
                  r="10" x="0" y="0"
                  stroke-width="4"
                  fill="#fff"
                  fill-opacity="0.4"
                  stroke="#fff"/>
        </defs>
        <grid-component
            :size="cellSize"
            :SVGParams="SVGParams"
        ></grid-component>
        <g ref="annotations" class="foreground">
          <g style="display: none" v-if="false">
            <g>
              <path
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  d="M.4,15A14.65,14.65,0,0,1,15,.4,14.66,14.66,0,0,1,29.51,15,15,15,0,0,1,15,29.51,14.37,14.37,0,0,1,.4,15Z"/>
              <path
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  d="M.4,36.33A14.65,14.65,0,0,1,15,21.78a14.55,14.55,0,1,1,0,29.1A14.36,14.36,0,0,1,.4,36.33Z"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="15.04" y1="6.95" x2="21.96" y2="18.96"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="21.96" y1="18.96" x2="7.77" y2="18.96"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="7.77" y1="18.96" x2="15.04" y2="6.95"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="14.5" y1="36.33" x2="5.86" y2="31.33"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="14.5" y1="36.33" x2="23.59" y2="31.33"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="14.5" y1="36.33" x2="14.5" y2="46.34"/>
              <rect x="0" y="0" fill="transparent" width="30" height="50"></rect>
            </g>
            <g>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="6.31" y1="7.05" x2="6.31" y2="18.87"/>
              <polyline
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  points="10.4 43.89 6.31 39.8 1.76 43.89"/>
              <rect
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x="0.4" y="18.87" width="11.37" height="29.56"/>
              <polyline
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  points="10.4 30.7 6.31 34.79 1.76 30.7"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="11.77" y1="23.42" x2="0.4" y2="23.42"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="11.77" y1="26.15" x2="0.4" y2="26.15"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="0.85" y1="56.17" x2="11.31" y2="56.17"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="2.67" y1="58.44" x2="9.5" y2="58.44"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="4.49" y1="60.26" x2="7.68" y2="60.26"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="6.31" y1="34.79" x2="6.31" y2="26.15"/>
              <line
                  style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:0.8px;"
                  x1="6.31" y1="39.8" x2="6.31" y2="56.17"/>
              <rect x="0" y="0" fill="transparent" width="15" height="60"></rect>
            </g>
            <template v-for="(voltageLevel, key) in data">
              <template v-for="(bb, index) in voltageLevel">
                <template v-if="key === '6кВ' || key === '10кВ'">
                  <line fill="none" stroke="#009919" stroke-width="3" stroke-miterlimit="10"
                        v-bind:x1="getBusPosition(index, key)[0]" y1="250"
                        v-bind:x2="getBusPosition(index, key)[1]" y2="250"/>
                  <text
                      v-bind:transform="'matrix(1 0 0 1 ' + (getBusPosition(index, key)[0] - 20) + ' 280)'"
                      font-family="'Arial'" font-size="12px">{{ bb.busbursection.name }}
                  </text>
                  <template v-for="(terminal,position) in bb.terminals">
                    <g
                        v-bind:transform="'translate(' + (position * 70 + getBusPosition(index, key)[0] - 10) + ', 35) scale(1.7)'">
                      <g>
                        <g v-if="terminal.objects">
                          <g v-for="(object) in terminal.objects">
                            <g v-if="object.className == 'CableBox'"
                               transform="transtate(0,0)">
                              <svg-cablebox-component stroke="#009919"
                                                      v-bind:rotated="false"/>
                            </g>
                            <g transform="translate(53.99,145) rotate(180)"
                               v-else-if="object.className == 'DisconnectorFuse'">
                              <svg_disconnectorfuse-component stroke="#009919"
                                                              v-bind:normalopen="object.normalopen"/>
                            </g>
                            <svg-loadbreakswitch-component
                                v-else-if="object.className == 'LoadBreakSwitch'"
                                stroke="#009919"
                                v-bind:normalopen="object.normalopen"
                            />
                            <svg-disconnector-component
                                v-else-if="object.className == 'Disconnector'"
                                stroke="#009919"
                                v-bind:normalopen="object.normalopen"
                                v-bind:rotated="false"
                            />
                            <g v-else-if="object.className == 'CurrentTransformer'"
                               transform="translate(0, 0)">
                              <svg-currenttransformer-component stroke="#009919"
                                                                v-bind:rotated="false"
                              />
                            </g>
                          </g>
                        </g>
                        <g v-else>
                          <line fill="none" stroke="#009919" stroke-miterlimit="10" x1="26.9"
                                y1="126" x2="26.9" y2="71.3"/>
                          <g transform="translate(0,0)">
                            <svg-cablebox-component stroke="#009919"
                                                    v-bind:rotated="false"/>
                          </g>
                        </g>
                      </g>
                      <circle fill="#FFFFFF" stroke="#009919" stroke-width="0.5"
                              stroke-miterlimit="10" cx="26.9" cy="126" r="1.5"/>
                      <text transform="matrix(1 0 0 1 24.4033 134.7968)" font-family="'Arial'"
                            font-size="8px">{{ terminal.busBarConnectionDotNumber }}
                      </text>
                      <rect x="17.7" y="11.1" fill="none" width="18.4" height="53.7"/>
                      <foreignObject x="0" y="-7" width="150" height="200"
                                     transform="matrix(3.464102e-07 -1 1 3.464102e-07 29.6949 64.7885)">
                        <p xmlns="http://www.w3.org/1999/xhtml"
                           style="font-size: 8px; font-family: Arial; line-height: 10px; padding: 0; margin: 0; color:black;  text-shadow: 1px 1px 1px #ffffff; ">
                            <span v-if="terminal.connected" style="color:red">
                                <a v-bind:href="'/admin/acline/map/edit/' + terminal.connectedLineId"
                                   v-bind:title="terminal.connectedLineName" target="_blank"
                                   onMouseOver="this.style.textDecoration='underline'"
                                   onMouseOut="this.style.textDecoration='none'"
                                >{{ terminal.name }}</a>
                            </span>
                          <span v-else>{{ terminal.name }}</span>
                        </p>
                      </foreignObject>
                      <text transform="matrix(3.464102e-07 -1 1 3.464102e-07 29.6949 64.7885)"
                            font-family="'Arial'" font-weight="bold" font-size="8px">

                      </text>
                    </g>
                  </template>
                </template>
                <g v-else transform="translate(0, 150)">
                  <line fill="none" stroke="#000099" stroke-width="3" stroke-miterlimit="10"
                        v-bind:x1="getBusPosition(index, key)[0]" y1="350"
                        v-bind:x2="getBusPosition(index, key)[1]" y2="350"/>
                  <text
                      v-bind:transform="'matrix(1 0 0 1 ' + (getBusPosition(index, key)[0] - 20) + ' 325)'"
                      font-family="'Arial'" font-size="12px">{{ bb.busbursection.name }}
                  </text>
                  <g v-for="(terminal,position) in bb.terminals">
                    <g
                        v-bind:transform="'translate(' + (position * 70 + getBusPosition(index, key)[0] - 10) + ', 315) scale(1.7)'">
                      <g>
                        <g v-if="terminal.objects">
                          <!--<line fill="none" stroke="#000099" stroke-miterlimit="10" x1="26.8" y1="21.6" x2="26.8" y2="76.4"/>-->
                          <g v-for="(object) in terminal.objects">
                            <g transform="translate(0.5,7)"
                               v-if="object.className == 'CableBox'">
                              <svg-cablebox-component stroke="#000099"
                                                      v-bind:rotated="true"/>
                            </g>
                            <g transform="translate(-0.2,4)" stroke="#000099"
                               v-else-if="object.className == 'DisconnectorFuse'">
                              <svg_disconnectorfuse-component stroke="#000099"
                                                              v-bind:normalopen="object.normalopen"/>
                            </g>
                            <svg-loadbreakswitch-component
                                v-else-if="object.className == 'LoadBreakSwitch'"
                                stroke="#000099"
                                v-bind:normalopen="object.normalopen"
                                v-bind:rotated="true"
                            />
                            <svg-disconnector-component
                                v-else-if="object.className == 'Disconnector'"
                                stroke="#000099"
                                v-bind:normalopen="object.normalopen"
                                v-bind:rotated="true"
                            />
                            <g v-else-if="object.className == 'CurrentTransformer'"
                               transform="translate(0, -12)">
                              <svg-currenttransformer-component stroke="#000099"
                                                                v-bind:rotated="false"
                              />
                            </g>
                          </g>
                        </g>
                        <g v-else>
                          <line fill="none" stroke="#000099" stroke-miterlimit="10" x1="26.8"
                                y1="21.6" x2="26.8" y2="76.4"/>
                          <g transform="translate(0,0)">
                            <svg-cablebox-component stroke="#000099"
                                                    v-bind:rotated="true"/>
                          </g>
                        </g>
                      </g>
                      <circle fill="#FFFFFF" stroke="#000099" stroke-width="0.5"
                              stroke-miterlimit="10" cx="26.8" cy="21.6" r="1.5"/>
                      <text transform="matrix(1 0 0 1 24.3389 17.9928)" font-family="'Arial'"
                            font-size="8px">{{ terminal.busBarConnectionDotNumber }}
                      </text>
                      <rect x="17.7" y="82.8" fill="none" width="18.4" height="53.7"/>
                      <foreignObject x="-175" y="-7" width="150" height="200"
                                     transform="matrix(3.464102e-07 -1 1 3.464102e-07 29.6949 64.7885)">
                        <p xmlns="http://www.w3.org/1999/xhtml"
                           style="font-size: 8px; font-family: Arial; line-height: 10px; padding: 0; margin: 0; text-align: right; color:black;  text-shadow: 1px 1px 1px #ffffff;">
                            <span v-if="terminal.connected" style="color:red">
                                <a v-bind:href="'/admin/acline/map/edit/' + terminal.connectedLineId"
                                   v-bind:title="terminal.connectedLineName" target="_blank"
                                   onMouseOver="this.style.textDecoration='underline'"
                                   onMouseOut="this.style.textDecoration='none'"
                                >{{ terminal.name }}</a>
                            </span>
                          <span v-else>{{ terminal.name }}</span>
                        </p>
                      </foreignObject>
                    </g>
                  </g>
                </g>
              </template>
            </template>
          </g>
          <g v-for="(item, index) in transformerLines" :key="'t-c-' + item.id">
            <transformerConnect :itemData="item"/>
          </g>
          <slot name="fixed"></slot>
          <!--Rendered-->
          <g v-for="(item, index) in elementsList" :key="item.id">
            <g>
              <svg_component :changeData="changeData" :cellSize="cellSize" :s-v-g-params="SVGParams"
                             :itemData="item"
                             :parentRefs="$refs"></svg_component>
            </g>
          </g>
          <g style="pointer-events: none">
            <!--Drawing-->
            <template v-if="line.creationLine.length">
              <line stroke="white" stroke-width="1"
                    :x1="line.creationLine[0].x"
                    :y1="line.creationLine[0].y"
                    :x2="line.creationLine[1] ? line.creationLine[1].x : stickyX"
                    :y2="line.creationLine[1] ? line.creationLine[1].y : stickyY">
              </line>
            </template>
            <template v-if="junction.creationJunction.length">
              <line stroke="white" stroke-width="5"
                    :x1="junction.creationJunction[0].x"
                    :y1="junction.creationJunction[0].y"
                    :x2="junction.creationJunction[1] ? junction.creationJunction[1].x : stickyX"
                    :y2="junction.creationJunction[1] ? junction.creationJunction[1].y : stickyY">
              </line>
            </template>
            <template v-if="rect.creationRect.length">
              <rect stroke="white" stroke-width="1" fill="none"
                    :x="rect.creationRect[0].x"
                    :y="rect.creationRect[0].y"
                    :width="(stickyX - rect.creationRect[0].x) < 0 ? 0 : stickyX - rect.creationRect[0].x"
                    :height="(stickyY - rect.creationRect[0].y) < 0 ? 0 : stickyY - rect.creationRect[0].y"></rect>
            </template>
            <template v-if="text.creationText.length">
              <g>
                <text style="opacity: .7" stroke="white"
                      :x="text.creationText[0].x"
                      :y="text.creationText[0].y"
                >
                  {{ text.value }}
                </text>
              </g>
            </template>
          </g>
        </g>
        <template v-if="Object.keys(getTemplate(activeTool)).length > 0">
          <g
              fill="none"
              :transform="`matrix(1 0 0 1 ${stickyX} ${stickyY - templateLowestY})`"
          >
            <g :transform="getTemplate(activeTool).access !== 'bottom-tire' && 'scale(1, -1)'">
              <template v-for="item in getTemplate(activeTool).elements">
                <template v-if="item.type === 'element'">
                  <svg_component v-if="item.hidden === false" :changeData="changeData" :cellSize="cellSize" :s-v-g-params="SVGParams"
                                 :itemData="{
                                 ...item,
                                 textOptions: {
                                   ...item.textOptions,
                                   reversed: getTemplate(activeTool).access !== 'bottom-tire'
                                 }
                                 }"
                                 :parentRefs="$refs"></svg_component>
                </template>
                <template v-else>
                  <svg_component :changeData="changeData" :cellSize="cellSize" :s-v-g-params="SVGParams"
                                 :itemData="{
                                 ...item,
                                 textOptions: {
                                 ...item.textOptions,
                                 reversed: getTemplate(activeTool).access !== 'bottom-tire'
                                 }
                                 }"
                                 :parentRefs="$refs"></svg_component>
                </template>

              </template>
            </g>
          </g>
        </template>
        <circle
            v-if="(Object.keys(getTemplate(activeTool)).length > 0 || activeTool === 'line' || activeTool === 'rect'
                    || activeTool === 'transformer' || activeTool === 'grounding' || activeTool.split(' ')[0] === 'element'
                    || activeTool === 'junction' ||  activeTool === 'three_phase_transformer') && mousemove"
            :cx="stickyX" :cy="stickyY" r="4" fill="white"></circle>
      </svg>
    </div>
  </div>
</template>

<script>
import {mapGetters} from 'vuex';
import manipulate from '../../mixins/manipulate';
import common from '../../mixins/common';
import SVG from 'svg.js';
import transformerConnect from './components/transformer-connect';

export default {
  name: 'svg_area',
  mixins: [manipulate, common],
  props: {
    data: {},
    addElement: Function,
    elementsList: Array,
    activeTool: String,
    cellSize: Number,
    SVGParams: Object,
    changeData: Function,
  },
  components: {
    transformerConnect,
  },
  data() {
    return {
      annotations: SVG.adopt(this.$refs.annotations),
      mousePosition: {
        x: null,
        y: null,
      },
      line: {
        pointsCount: 2,
        creationLine: [],
      },
      junction: {
        pointsCount: 1,
        creationJunction: [],
      },
      rect: {
        pointsCount: 2,
        creationRect: [],
      },
      text: {
        pointsCount: 1,
        value: '',
        creationText: [],
      },
      template: {
        pointsCount: 1,
        creationTemplate: {},
        data: {},
      },
      element: {
        pointsCount: 1,
        creationElement: [],
      },
      mousemove: false,
      isMounted: false,
    };
  },
  mounted() {
    this.isMounted = true;
    this.checkSVGSize();

  },
  computed: {
    ...mapGetters(['templatesList', 'activeTemplate']),
    SVGX() {
      if (this.isMounted && this.mousePosition.x !== null) {
        return this.ratioMultiplier(this.mousePosition.x);
      }
    },
    SVGY() {
      if (this.isMounted && this.mousePosition.y !== null) {
        return this.ratioMultiplier(this.mousePosition.y);
      }
    },
    stickyX() {
      let result = Math.round(this.SVGX / this.cellSize) * this.cellSize;
      return result ? result : 0
    },
    stickyY() {
      let result = Math.round(this.SVGY / this.cellSize) * this.cellSize;
      return result ? result : 0
    },
    cursor() {
      if (this.activeTool === 'text') {
        return 'text';
      }
      return false;
    },
    transformerLines() {
      return this.elementsList
          ? this.elementsList.filter(e => e.type === 'transformer')
          : [];
    },
    templateLowestY() {
      if (this.getTemplate(this.activeTool)) {
        let lowest = Number.POSITIVE_INFINITY
        this.getTemplate(this.activeTool).elements.map(el => {
          el.coords.map(coord => {
            if (lowest > coord.y) {
              lowest = coord.y
            }
          })
        })
        return lowest !== Number.POSITIVE_INFINITY ? lowest : 0
      }
      return 0
    }
  },
  watch: {
    activeTool(value) {
      if (value === 'top-tire' || value === 'bottom-tire') {
        this.createTire(value);
      }
    },
  },
  methods: {
    getDefaultParams(type) {
      return {
        id: this.generateId(),
        type: type,
        interactable: true,
        coords: [],
        caption: '',
        textCoords: {x: 0, y: 0},
        textOptions: {
          alignment: 'start',
          fontSize: 11,
          orientation: 'horizontal',
        },
      };
    },
    getTemplate(id) {
      const template = this.templatesList.filter((template) => template.id === id)[0];
      if (template !== undefined) return JSON.parse(JSON.stringify(template));
      else return {};
    },
    createTire(value) {
      const data = this.getDefaultParams(value);
      // data.interactable = false;
      data.connection = [];
      data.beforeTire = null;
      data.afterTire = null;
      data.class = "BusbarSection";
      data.mark = {key: 'busbarsectioninfo', value: null, caption: null};
      data.additionalEquipment = {
        elements: [],
        key: data.mark.key
      };
      data.voltageLevel = {key: 'baseVoltage', value: null, caption: null, id: this.generateId()};

      this.figureSave(data);
    },
    updateMousePosition(e) {
      this.mousePosition.x = e.offsetX;
      this.mousePosition.y = e.offsetY;
    },
    takePoint() {
      if (this.activeTool === 'line') {
        this.line.creationLine.push({x: this.stickyX, y: this.stickyY});

        if (this.line.creationLine.length === this.line.pointsCount) {
          const data = this.getDefaultParams(this.activeTool);

          this.line.creationLine.forEach(el => {
            data.coords.push({x: el.x, y: el.y});
          });

          this.figureSave(data);
          this.line.creationLine.splice(0);
        }
      }
      if (this.activeTool === 'junction') {
        if (this.activeTemplate.access === 'between-tire') {
          this.junction.creationJunction.push({x: this.stickyX, y: this.SVGParams.height / 2});
        } else {
          this.junction.creationJunction.push({x: this.SVGParams.width / 2, y: this.stickyY});
        }

        if (this.junction.creationJunction.length === this.junction.pointsCount) {
          const data = this.getDefaultParams(this.activeTool);

          this.junction.creationJunction.forEach(el => {
            data.coords.push({x: el.x, y: el.y});
          });

          this.figureSave(data);
          this.junction.creationJunction.splice(0);
        }
      }
      if (Object.keys(this.getTemplate(this.activeTool)).length > 0) {
        this.template.creationTemplate = {x: this.stickyX, y: this.stickyY};

        const data = this.getDefaultParams('custom-cell');
        data.templateData = this.getTemplate(this.activeTool);
        data.admittance = this.getTemplate(this.activeTool).access;


        data.coords.push({x: this.template.creationTemplate.x, y: this.template.creationTemplate.y});
        data.reverse = this.getTemplate(this.activeTool).access === 'bottom-tire';
        if (data.admittance === 'between-tire') {
          data.index2 = null;
          data.index = null;
        } else {
          data.index = null;

          data.coords[0].y = data.admittance === 'top-tire' ? 100 : 760;
        }
        data.class = "Bay"
        data.currentTireID = null;
        data.voltageLevel = {key: 'baseVoltage', value: null, caption: null, id: this.generateId()};
        this.figureSave(data);
        this.template.creationTemplate = {};
      }
      if (this.activeTool === 'rect') {
        this.rect.creationRect.push({x: this.stickyX, y: this.stickyY});

        if (this.rect.creationRect.length === this.rect.pointsCount) {
          const data = this.getDefaultParams(this.activeTool);

          this.rect.creationRect.forEach(el => {
            data.coords.push({x: el.x, y: el.y});
          });

          this.figureSave(data);
          this.rect.creationRect.splice(0);
        }
      }
      if (this.activeTool === 'text') {
        this.text.creationText.push({x: this.SVGX, y: this.SVGY});

        const data = this.getDefaultParams(this.activeTool);
        data.gridDisabled = true;
        data.value = '';
        delete data.caption;

        this.text.creationText.forEach(el => {
          data.coords.push({x: el.x, y: el.y});
        });

        this.figureSave(data);
        this.text.creationText.splice(0);
      }
      if (this.activeTool === 'transformer') {
        const data = this.getDefaultParams(this.activeTool);
        data.mark = {key: '/oldTransformerTankInfo', value: null, caption: null};
        data.additionalEquipment = {
          elements: [],
          key: data.mark.key
        };
        data.phase_literals = []
        data.coords = [{x: this.stickyX, y: this.stickyY}];
        data.income = {
          id: null,
          connection: 'tire',
          mark: {key: 'busbarsectioninfo', value: null, caption: null},
          length: null,
          size: null,
          markcable: {key: 'busbarsectioninfo', value: null, caption: null},
        };
        data.free_connection = {
          id: null,
          connection: 'tire',
          mark: {key: 'busbarsectioninfo', value: null, caption: null},
          length: null,
          size: null,
          markcable: {key: 'busbarsectioninfo', value: null, caption: null},
          tire: null
        };
        data.outcome = {
          id: null,
          connection: 'tire',
          mark: {key: 'busbarsectioninfo', value: null, caption: null},
          length: null,
          size: null,
          markcable: {key: 'busbarsectioninfo', value: null, caption: null},
        };
        data.class = 'PowerTransformer';
        data.phase_count = {
          caption: 'двухобмоточный',
          value: '2'
        }
        data.connectedTires = []

        this.figureSave(data);
      }
      if (this.activeTool.split(' ')[0] === 'element') {
        this.element.creationElement.push({x: this.stickyX, y: this.stickyY});

        const data = this.getDefaultParams('element');
        data.elementName = this.activeTool.split(' ')[1];
        data.gridDisabled = true;

        this.element.creationElement.forEach(el => {
          data.coords.push({x: el.x, y: el.y});
        });
        data.class = "Bay";

        this.figureSave(data);

        this.element.creationElement.splice(0);
      }
    },
    figureSave(data) {
      this.$store.dispatch('manuallyCreating', true);
      this.addElement(data);
    },
    checkSVGSize() {
      if (this.$refs.editorSVGWrap.offsetWidth > 0 && this.$refs.editorSVGWrap.offsetHeight > 0) {
        this.SVGParams.realWidth = this.$refs.editorSVGWrap.offsetWidth;
        this.SVGParams.realHeight = this.$refs.editorSVGWrap.offsetHeight;

        window.addEventListener('resize', () => {
          this.SVGParams.realWidth = this.$refs.editorSVGWrap.offsetWidth;
          this.SVGParams.realHeight = this.$refs.editorSVGWrap.offsetHeight;
        });

      }
    },
    getBusPosition(index, key) {
      const width = 70;
      let maxes = [];
      let kl = 0;
      let maxBuses = 0;
      for (let bus in this.data) {
        maxes[kl] = [];
        let i = 0;
        for (let busbarsection in this.data[bus]) {
          maxes[kl][i] = this.data[bus][busbarsection]['terminals'].length;
          i++;
          if (i > maxBuses) maxBuses = i;
        }
        kl++;
      }
      let max = 0;
      let startx = 0;
      let endX = 0;
      for (let i = 0; i < maxBuses; i++) {
        if (maxes[1]) max = maxes[0][i] >= maxes[1][i] ? maxes[0][i] : maxes[1][i];
        else max = maxes[0][i];
        if (i == 0) {
          startx = 0;
          endX = startx + (width * max);

        }
        if (i > 0) {
          startx = endX + 40;
          endX = startx + (width * max);
        }
        if (i == index) {
          if (this.data[key][index]['terminals'].length < max) {
            let busWidthDelim = ((width * max) - this.data[key][index]['terminals'].length * width) / 2;
            return [startx + busWidthDelim, endX - busWidthDelim];
          } else return [startx, endX];
        }
      }
    },
  },
};
</script>

<style scoped>

</style>
