class Item < ActiveRecord::Migration
  def self.up
    create_table :items do |t|
      t.integer :user_id
      t.string  :name
      t.string  :description
      t.float   :value
      t.boolean :available
    end
    create_table :sharing_limits do |t|
      t.integer :lender_id
      t.integer :borrower_id
      t.float   :upper_limit
    end
  end

  def self.down
    drop_table :sharing_limits
    drop_table :items
  end
end
