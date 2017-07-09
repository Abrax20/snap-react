import createReducer from '../lib/createReducer'
import * as types from '../actions/types'

export const searchedRecipes = createReducer({}, {
  [types.ADD_SNAP](state, action) {
    let newState = {}
    action.snap = { added: true };
    // action.recipes.forEach( (recipe) => {
    //   newState[id] = Object.assign({}, recipe, { id });
    // });
    return newState;
  },

});
