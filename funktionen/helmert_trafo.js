/**
 * Class representing a 4 parameter Helmert Transformation.
 * Instanciate with the classical geodetic parameters.
 *
 * @param y0
 * @param x0
 * @param o
 * @param a
 *
 * @author Christian Mayer
 *
 * @version 1.1-dev
 *
 * @license BSD see license.txt
 *
 * @Example
 *
 * Example call:
 *
 *    // The parameter of the transformation
 *    var y0 = 200996.148;
 *    var x0 = 1251460.485
 *    var o =  -0.801798904;
 *    var a =  0.597593941;
 *
 *    var helmertTransformation = new HelmertTransformation4Js(y0, x0, o, a);
 *
 */
function HelmertTransformation4Js(y0, x0, o, a) {
    /**
     * The Y0 translation parameter
     */
    this.y0 = y0;

    /**
     * The X0 translation parameter
     */
    this.x0 = x0;

    /**
     * The 'o' parameter having the scale and rotation
     */
    this.o = o;

    /**
     * The 'a' parameter having the the scale and rotation
     */
    this.a = a;
}

/**
* Calculate 4 prameter for a 2D Helmert transformation
* @param identPoints an array of pairs of points identical in local and world coordinate system
* @returns {Object} a Plan JS-object containing the x0 and y0 coordinates of the center point and
* a and o parameter for the 4 parameter Helmert Transformation
*
* Example call:
	t = new HelmertTransformation4Js(0, 0, 0, 0);
	t.calcTransformationParameter([
    {
      "local" : {
      	"y": 0,
       	"x": 10
      },
      "world" : {
       	"y": 500000,
       	"x": 6000000
      }
    }, {
      "local" : {
       	"y": 0,
       	"x": 121.80
      },
      "world" : {
     		"y": 500100,
       	"x": 6000050
      }
    }
  ]);
  pWorld = t.transformToWorld(0, 65.9, true);
	pLocal = t.transformToLocal(500050, 6000025, true);
*/
HelmertTransformation4Js.prototype.calcTransformationParameter = function(identPoints) {
  // local Punkte des Ausgangssystems
  // world Punkte des Zielsystems
  var roh = 180 / Math.PI,
      // Anzahl der identischen Punkte
      n = identPoints.length,
      // Summe der x und y Koordinaten von local und world
      sum = identPoints.reduce(function(a, b) {
        return {
          "local": { "y": a.local.y + b.local.y, "x": a.local.x + b.local.x },
          "world": { "y": a.world.y + b.world.y, "x": a.world.x + b.world.x }
        }
      }),
      // Averages of y and x of world and local coordinates
      center = {
        "local": { "y": sum.local.y/n,  "x": sum.local.x/n},
        "world": { "y": sum.world.y/n,  "x": sum.world.x/n}
      },
      centerPoints = identPoints.map(function(iP) {
        return {
          "local": { "y": iP.local.y - center.local.y, "x": iP.local.x - center.local.x },
          "world": { "y": iP.world.y - center.world.y, "x": iP.world.x - center.world.x }
        }
      }),
      sumCenterPoints = centerPoints.reduce(function(a, b) { return a.local.y + b.local.y + a.local.x + b.local.x + a.world.y + b.world.y + a.world.x + b.world.x}),
      c = centerPoints.reduce(function(a, b) { return a.local.x * a.local.x + a.local.y * a.local.y + b.local.x * b.local.x + b.local.y * b.local.y; });

  this.o = centerPoints.reduce(function(a, b) { return a.local.x * a.world.y - a.local.y * a.world.x + b.local.x * b.world.y - b.local.y * b.world.x; }) / c;
  this.a = centerPoints.reduce(function(a, b) { return a.local.x * a.world.x + a.local.y * a.world.y + b.local.x * b.world.x + b.local.y * b.world.y; }) / c;
  this.y0 = center.world.y - this.o * center.local.x - this.a * center.local.y;
  this.x0 = center.world.x - this.a * center.local.x + this.o * center.local.y;

  var
    this_ = this;
    transPoints = identPoints.map(function(iP) {
      return this_.transformToWorld(iP.local.x, iP.local.y, true);
    });
		
  return {};
}

/**
 * Calculate transformation from origin to destination
 * as defined in the parameter.
 *
 * For example from local grid to national grid.
 *
 * @param y an Y coordinate in your destination system to be transformed
 * @param x a X coordinate in your destination system to be transformed
 * @param round: number of round digits
 *
 * @returns {Object} Plain JS-object representing the transformed point
 */
HelmertTransformation4Js.prototype.transformToWorld = function(y, x, round) {

    var xIn = x;
    var yIn = y;

    if (typeof round === "undefined" || round === null) {
        round = 14;
    }

    y = this.y0 + this.o * xIn + this.a * yIn;
    x = this.x0 + this.a * xIn - this.o * yIn;

    if (round) {
        y = parseFloat(roundNumber(y, round));
        x =  parseFloat(roundNumber(x, round));
    }

    return [y,x];
};

/**
 * Calculate transformation from destination to origin
 * as defined in the parameter.
 *
 * For example from national grid to local grid.
 *
 * @param y an Y-coordinate in your destination system to be transformed
 * @param x a X-coordinate in your destination system to be transformed
 * @param round: number of round digits
 *
 * @returns {Object} Plain JS-object representing the transformed point
 */
HelmertTransformation4Js.prototype.transformToLocal = function(y, x, round) {

    if (typeof round === "undefined" || round === null) {
        round = 14;
    }

    var aT = this.a / (Math.pow(this.a, 2) + Math.pow(this.o, 2)),
        oT = this.o / (Math.pow(this.a, 2) + Math.pow(this.o, 2)),
        y0Rev = -this.x0 * oT + this.y0 * aT,
        x0Rev = this.x0 * aT + this.y0 * oT,
        xIn = x,
        yIn = y;

    y = -y0Rev + aT * yIn - oT * xIn;
    x = -x0Rev + aT * xIn + oT * yIn;

    if (round) {
				console.log(y);
				console.log(x);
        y = parseFloat(roundNumber(y, round));
        x =  parseFloat(roundNumber(x, round));
    }
    return [x,y];
};