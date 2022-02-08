import interact from 'interactjs';
import SVG from 'svg.js';

export default {
  props: {
    inertia: Boolean,
    grid: {
      type: [Array, Number],
      validator: (value) => (value.length === 2 && value.every(v => typeof v === 'number')) || (typeof (value) === 'number'),
    },
  },
  data() {
    return ({
      editableFirstLoad: true,
      applyTransforms: () => {
      },
    });
  },
  methods: {
    changeCoordinate(template) {
      if (template.access === 'between-tire') {
        template.elements.map((element) => {
          element.coords.map((coord) => {
            coord.y += 80;
          });
        });
      } else {
        template.elements.map((element) => {
          element.coords.map((coord) => {
            coord.x += 70;
          });
          // if (template.access === 'top-tire') {
          //   if (element.type === 'line') {
          //     element.coords[0].y = 160 - element.coords[1].y
          //     element.coords[1].y = 160
          //   } else if (element.type === 'junction') {
          //     element.coords[0].y = 160 - (element.coords[0].y + element.lineHeight)
          //   } else {
          //     element.coords[0].y = 160 - (element.coords[0].y + element.height)
          //     element.inverted180 = false
          //   }
          // }
        });
      }
      return template;
    },
    checkClosest(target, node) {
      return target === node
        ? true :
        target.parentElement
          ? this.checkClosest(target.parentElement, node)
          : false;
    },
    makeEditable(node) {
      const polygon = node.querySelector('polygon.editable');
      if (polygon) {
        const sns = 'http://www.w3.org/2000/svg';
        const xns = 'http://www.w3.org/1999/xlink';
        const root = this.$refs.editorSVG || this.parentRefs?.editorSVG;

        let rootMatrix;
        let originalPoints = [];
        let transformedPoints = [];

        for (let i = 0, len = polygon.points.numberOfItems; i < len; i++) {
          let handle = document.createElementNS(sns, 'use');
          let point = polygon.points.getItem(i);
          let newPoint = root.createSVGPoint();

          handle.setAttributeNS(xns, 'href', '#point-handle');
          handle.setAttribute('class', 'point-handle');

          handle.x.baseVal.value = newPoint.x = point.x;
          handle.y.baseVal.value = newPoint.y = point.y;

          handle.setAttribute('data-index', i);

          originalPoints.push(newPoint);
          polygon.parentNode.insertBefore(handle, polygon.nextSibling);
        }

        this.applyTransforms = () => {
          rootMatrix = root.getScreenCTM();
          transformedPoints = originalPoints.map((point) => point.matrixTransform(rootMatrix));

          interact('.point-handle').draggable({
            snap: {
              targets: transformedPoints,
              range: 20 * Math.max(rootMatrix.a, rootMatrix.d),
            },
          });
        };

        interact(root)
          .on('mousedown', this.applyTransforms)
          .on('touchstart', this.applyTransforms);

        let x = 0;
        let y = 0;

        interact('.point-handle')
          .draggable({
            onstart: event => {
              x = event.client.x;
              y = event.client.y;
            },
            onend: event => {
              const offsetX = event.client.x - x;
              const offsetY = event.client.y - y;

              this.$emit('move-end', {
                x: this.ratioMultiplier(offsetX),
                y: this.ratioMultiplier(offsetY),
                target: SVG.adopt(event.target),
                index: event.target.getAttribute('data-index') | 0,
              });
            },
            onmove: event => {
              let i = event.target.getAttribute('data-index') | 0;
              // let point = polygon.points.getItem(i);

              this.$emit('move-point', {
                x: event.dx / rootMatrix.a,
                y: event.dy / rootMatrix.d,
                target: event.target,
                index: i,
              });

              // point.x += event.dx / rootMatrix.a;
              // point.y += event.dy / rootMatrix.d;
              //
              // event.target.x.baseVal.value = point.x;
              // event.target.y.baseVal.value = point.y;
            },
            snapSize: {
              targets: [
                {width: this.ratioMultiplierRevert(this.cellSize)},
                interact.snappers.grid({
                  width: this.ratioMultiplierRevert(this.cellSize),
                  height: this.ratioMultiplierRevert(this.cellSize),
                }),
              ],
              relativePoints: [
                {x: 0, y: 0},
              ],
            },
            restrict: {
              restriction: 'svg',
              elementRect: {top: 0, left: 0, bottom: 1, right: 1},
            },
          })
          .styleCursor(true);
      }
    },

    destroyEditable(node) {
      const points = node.parentElement.querySelectorAll('.point-handle');
      const root = this.$refs.editorSVG || this.parentRefs?.editorSVG;

      interact(root)
        .off('mousedown', this.applyTransforms)
        .off('touchstart', this.applyTransforms);

      interact(root).unset();
      interact('.point-handle').unset();

      points.forEach($el => {
        $el.remove();
      });
    },

    makeInteractable(node, itemData, type) {
      interact(node).unset();

      let x = 0;
      let y = 0;

      return interact(node)
        .draggable({
          inertia: this.inertia,
          snapSize: !itemData?.gridDisabled ? {
            targets: [
              {width: this.ratioMultiplierRevert(this.cellSize)},
              interact.snappers.grid({
                width: this.ratioMultiplierRevert(this.cellSize),
                height: this.ratioMultiplierRevert(this.cellSize),
              }),
            ],
            relativePoints: [
              {x: 0, y: 0},
            ],
          } : {},
          restrict: {
            restriction: 'svg',
            elementRect: {top: 0, left: 0, bottom: 1, right: 1},
          },
          autoScroll: true,
          onstart: event => {
            x = event.client.x;
            y = event.client.y;

            this.$emit(type === 'text' ? 'move-start-text' : 'move-start');
          },
          onend: event => {
            const offsetX = event.client.x - x;
            const offsetY = event.client.y - y;

            this.$emit(type === 'text' ? 'move-end-text' : 'move-end', {
              x: this.ratioMultiplier(offsetX),
              y: this.ratioMultiplier(offsetY),
              target: SVG.adopt(event.target),
            });
          },
          onmove: event => {
            const offsetX = event.client.x - x;
            const offsetY = event.client.y - y;

            const xPos = this.ratioMultiplier(event.dx);
            const yPos = this.ratioMultiplier(event.dy);

            this.$emit(type === 'text' ? 'move-text' : 'move', {
              x: xPos,
              y: yPos,
              target: SVG.adopt(event.target),
              mx: this.ratioMultiplier(offsetX),
              my: this.ratioMultiplier(offsetY),
            });
          },
        });
    },

    ratioMultiplier(value) {
      let result = this.SVGParams.width / this.SVGParams.realWidth * value;
      if (typeof result === 'number' && result !== Infinity) {
        return result;
      } else {
        return 0;
      }
    },
    ratioMultiplierRevert(value) {
      return this.SVGParams.realHeight / this.SVGParams.height * value;
    },
  },

  mounted() {

  },

  computed: {},
};
