class PhpSetup < ActiveRecord::Migration
  def self.up
    create_table :items do |t|
      t.integer   :item_id
      t.integer   :who
      t.integer   :what
      t.string    :description
      t.float     :value
      t.datetime  :time
      t.boolean   :available
    end
    create_table :trust do |t|
      t.integer :sharer
      t.integer :borrower
      t.float   :share_limit
    end
  end

  def self.down
    drop_table :trust
    drop_table :items
  end
end
