class ItemController < ApplicationController
  def index
    @items = Item.find(:all, :conditions => {:id => params[:user_id]})
  end

  def new
    @item = Item.new
  end

  def create
    @item = Item.new(params[:item])
    if @item.save
      flash[:notice] = "Item was successfully created."
    else
      flash[:notice] = "An error occurred while trying to create this item."
    end
  end

  def edit
    @item = Item.find(params[:item_id])
  end

  def update
    Item.update_attributes(params[:item], params[:item][:id])
  end

  def destroy
    Item.destroy(params[:item_id])
  end
end
