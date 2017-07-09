import * as types from './types'
import Api from '../lib/api'

export function fetchRecipes(ingredients) {
  return (dispatch, getState) => {
  }
}

export function setSearchedRecipes({ recipes }) {
  return {
    type: types.ADD_SNAP,
    recipes,
  }
}
