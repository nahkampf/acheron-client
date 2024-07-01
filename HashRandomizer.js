/**
 * The HashRandomizer main object
 * @constructor
 * @param {string} [initHash] - Oprional initial hash value.
 *   If omitted a 36 character string will be randomized.
 * @returns {object} HashRandomizer - The HashRandomizer Object
 * @returns {function} HashRandomizer.randomHash - Hash generator
 * @returns {string} HashRandomizer.hash - Current hash
 * @returns {function} HashRandomizer.updateHash - Sets a new hash
 * @returns {function} HashRandomizer.random - Randomizer function
 */
const HashRandomizer = function (initHash) {
  const uriParams = Object.fromEntries(
  	[...(new URLSearchParams(window.location.search))]
  )
  
  // Private methods
  /** 
   * Sets up the hash randomizer with the supplied string as seed 
   * @param {string} hashString - Hash seed string
   * @returns {function} hashRandom - Hash based randomizer function
   */
  function setupHashRand(hashString) {
    /** Seed generator */
    const xmur3 = (str) => {
        for(var i = 0, h = 1779033703 ^ str.length; i < str.length; i++) {
            h = Math.imul(h ^ str.charCodeAt(i), 3432918353);
            h = h << 13 | h >>> 19;
        }

        return () => {
            h = Math.imul(h ^ (h >>> 16), 2246822507);
            h = Math.imul(h ^ (h >>> 13), 3266489909);
            return (h ^= h >>> 16) >>> 0;
        }
    }
    /** Randomizer generator */
    const xoshiro128ss = (a, b, c, d) => () => {
      var t = b << 9, r = a * 5; r = (r << 7 | r >>> 25) * 9;
      c ^= a; d ^= b;
      b ^= c; a ^= d; c ^= t;
      d = d << 11 | d >>> 21;
      return (r >>> 0) / 4294967296;
    }
    const seed = xmur3(hashString);
    return xoshiro128ss(seed(), seed(), seed(), seed());
  }

  // Public methods/values
  /**
   * Hash generator method
   * @param {number} [len=36] - length of generated hash
   * @returns {string} - new generated hash string
   */
  this.randomHash = (len = 36)  => {
    const arr = new Uint8Array(len)
    self.crypto.getRandomValues(arr)
    return Array.from(arr, n => n.toString(36)).join('').substr(-len)
  }  
  /** Current hash string */  
  this.hash = initHash || uriParams.h || this.randomHash()
  
  let hashRand = setupHashRand(this.hash)

  /**
   * Hash update method, also updates the current url and updates history state
   * @param {string} newHash - the new hash value
   */  
  this.updateHash = (newHash) => {
    hashRand = setupHashRand(newHash)
    this.hash = newHash
    // Update location query string i.e. "?h="
    const url = new URL(window.location)
    url.searchParams.set("h", newHash);
    history.pushState(JSON.stringify({ hash: newHash }), "", url)
  }
  
  /**
   * Randomizer method
   * @param {array|number} [n] - randomizer function that accepts:
   *  - an Array and returns one item in that array
   *  - an Integer and returns a number between 0 and the Int
   *  - Anything falsy and return a Float between 0 and 1   
   * @returns {*|number} - see above
   */  
	this.random = (n) => n ?
    Array.isArray(n) ?
      n[~~(hashRand() * n.length)]
      : ~~(hashRand() * n)
    : hashRand();
  
  return this;
}
/// END OF HASH RANDOMIZER